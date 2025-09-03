<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorEarning;
use App\Models\Tutor;
use App\Services\TutorEarningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TutorEarningController extends Controller
{
    protected $earningService;

    public function __construct(TutorEarningService $earningService)
    {
        $this->earningService = $earningService;
    }

    /**
     * Hiển thị danh sách thu nhập chờ thanh toán
     */
    public function index()
    {
        $pendingEarnings = TutorEarning::with(['tutor.user', 'booking.subject'])
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->paginate(10);

        $processingEarnings = TutorEarning::with(['tutor.user', 'booking.subject'])
            ->where('status', 'processing')
            ->orderBy('created_at')
            ->paginate(10);

        $completedEarnings = TutorEarning::with(['tutor.user', 'booking.subject'])
            ->where('status', 'completed')
            ->latest('paid_at')
            ->paginate(10);

        // Thống kê tổng quan
        $stats = [
            'total_pending' => TutorEarning::where('status', 'pending')->sum('amount'),
            'total_processing' => TutorEarning::where('status', 'processing')->sum('amount'),
            'total_completed' => TutorEarning::where('status', 'completed')->sum('amount'),
            'total_platform_fee' => TutorEarning::whereIn('status', ['completed', 'processing'])->sum('platform_fee'),
            'count_pending' => TutorEarning::where('status', 'pending')->count(),
            'count_processing' => TutorEarning::where('status', 'processing')->count(),
        ];

        return view('admin.earnings.index', compact(
            'pendingEarnings',
            'processingEarnings',
            'completedEarnings',
            'stats'
        ));
    }

    /**
     * Hiển thị thông tin chi tiết một bản ghi thu nhập
     */
    public function show(TutorEarning $earning)
    {
        $earning->load(['tutor.user', 'booking.subject', 'booking.student', 'booking.payments']);
        return view('admin.earnings.show', compact('earning'));
    }

    /**
     * Hiển thị form chỉnh sửa bản ghi thu nhập
     */
    public function edit(TutorEarning $earning)
    {
        $earning->load(['tutor.user', 'booking.subject']);
        return view('admin.earnings.edit', compact('earning'));
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function update(Request $request, TutorEarning $earning)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'transaction_reference.max' => 'Mã giao dịch không được vượt quá 255 ký tự',
        ]);

        $this->earningService->updateEarningStatus(
            $earning,
            $validated['status'],
            $validated['transaction_reference'] ?? null,
            $validated['notes'] ?? null
        );

        return redirect()->route('admin.earnings.index')
            ->with('success', 'Đã cập nhật trạng thái thanh toán');
    }

    /**
     * Hiển thị danh sách thu nhập theo gia sư
     */
    public function tutorEarnings(Tutor $tutor)
    {
        $earnings = $tutor->earnings()
            ->with(['booking.subject', 'booking.student'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_paid' => $tutor->earnings()->where('status', 'completed')->sum('amount'),
            'total_pending' => $tutor->earnings()->whereIn('status', ['pending', 'processing'])->sum('amount'),
            'total_bookings' => $tutor->earnings()->count(),
        ];

        return view('admin.earnings.tutor', compact('tutor', 'earnings', 'stats'));
    }

    /**
     * Đánh dấu nhiều bản ghi là đang xử lý thanh toán
     */
    public function markAsProcessing(Request $request)
    {
        $validated = $request->validate([
            'earnings' => ['required', 'array'],
            'earnings.*' => ['required', 'exists:tutor_earnings,id'],
            'notes' => ['nullable', 'string'],
        ], [
            'earnings.required' => 'Vui lòng chọn ít nhất một khoản thu nhập',
            'earnings.array' => 'Danh sách khoản thu nhập không hợp lệ',
            'earnings.*.exists' => 'Khoản thu nhập không tồn tại',
        ]);

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($validated['earnings'] as $earningId) {
                $earning = TutorEarning::find($earningId);
                if ($earning && $earning->status === 'pending') {
                    $this->earningService->updateEarningStatus(
                        $earning,
                        'processing',
                        null,
                        $validated['notes'] ?? 'Đánh dấu hàng loạt là đang xử lý'
                    );
                    $count++;
                }
            }
            DB::commit();
            return redirect()->route('admin.earnings.index')
                ->with('success', "Đã đánh dấu {$count} khoản thanh toán là đang xử lý");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.earnings.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Đánh dấu nhiều bản ghi là đã thanh toán
     */
    public function markAsCompleted(Request $request)
    {
        $validated = $request->validate([
            'earnings' => ['required', 'array'],
            'earnings.*' => ['required', 'exists:tutor_earnings,id'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'earnings.required' => 'Vui lòng chọn ít nhất một khoản thu nhập',
            'earnings.array' => 'Danh sách khoản thu nhập không hợp lệ',
            'earnings.*.exists' => 'Khoản thu nhập không tồn tại',
            'transaction_reference.max' => 'Mã giao dịch không được vượt quá 255 ký tự',
        ]);

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($validated['earnings'] as $earningId) {
                $earning = TutorEarning::find($earningId);
                if ($earning && $earning->status === 'processing') {
                    $this->earningService->updateEarningStatus(
                        $earning,
                        'completed',
                        $validated['transaction_reference'] ?? null,
                        $validated['notes'] ?? 'Đánh dấu hàng loạt là đã thanh toán'
                    );
                    $count++;
                }
            }
            DB::commit();
            return redirect()->route('admin.earnings.index')
                ->with('success', "Đã đánh dấu {$count} khoản thanh toán là đã hoàn thành");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.earnings.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý tự động các buổi học đã hoàn thành
     */
    public function processCompletedBookings()
    {
        list($successCount, $failedCount) = $this->earningService->processCompletedBookings();
        
        return redirect()->route('admin.earnings.index')
            ->with('success', "Đã xử lý {$successCount} buổi học hoàn thành. Thất bại: {$failedCount}");
    }
} 