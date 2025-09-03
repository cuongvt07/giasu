<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Hiển thị danh sách môn học
     */
    public function index(Request $request)
    {
        $query = Subject::query()->where('is_active', true);
        
        // Tìm kiếm theo tên hoặc danh mục
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        
        // Lọc theo danh mục
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        $subjects = $query->orderBy('category')
                          ->orderBy('name')
                          ->get()
                          ->groupBy('category');
        
        // Lấy danh sách tất cả các danh mục
        $categories = Subject::where('is_active', true)
                            ->distinct()
                            ->pluck('category');

        return view('pages.subjects.index', [
            'subjects' => $subjects,
            'categories' => $categories,
            'search' => $request->search ?? '',
            'selectedCategory' => $request->category ?? ''
        ]);
    }

    /**
     * Hiển thị chi tiết môn học
     */
    public function show(Request $request, Subject $subject)
    {
        // Phân trang và lọc danh sách gia sư
        $tutors = $subject->tutors()
                    ->where('status', 'active')
                    ->where('is_verified', true)
                    ->when($request->filled('min_price'), function($query) use ($request) {
                        $query->where('tutor_subjects.price_per_hour', '>=', $request->min_price);
                    })
                    ->when($request->filled('max_price'), function($query) use ($request) {
                        $query->where('tutor_subjects.price_per_hour', '<=', $request->max_price);
                    })
                    ->when($request->filled('min_rating'), function($query) use ($request) {
                        $query->where('rating', '>=', $request->min_rating);
                    })
                    ->when($request->filled('sort'), function($query) use ($request) {
                        if ($request->sort == 'price_asc') {
                            $query->orderBy('tutor_subjects.price_per_hour', 'asc');
                        } else if ($request->sort == 'price_desc') {
                            $query->orderBy('tutor_subjects.price_per_hour', 'desc');
                        } else if ($request->sort == 'rating_asc') {
                            $query->orderBy('rating', 'asc');
                        } else if ($request->sort == 'rating_desc') {
                            $query->orderBy('rating', 'desc');
                        }
                    }, function($query) {
                        // Nếu không có tùy chọn sắp xếp, sử dụng ID mặc định
                        $query->orderBy('tutors.id', 'desc');
                    })
                    ->paginate(9)
                    ->withQueryString();

        return view('pages.subjects.show', [
            'subject' => $subject,
            'tutors' => $tutors,
            'filters' => [
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'min_rating' => $request->min_rating,
                'sort' => $request->sort ?? ''
            ]
        ]);
    }
}
