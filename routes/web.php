<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AiAdvisorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TutorController as AdminTutorController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;
use App\Http\Controllers\Tutor\ProfileController as TutorProfileController;
use App\Http\Controllers\Tutor\BookingController as TutorBookingController;
use App\Http\Controllers\Tutor\ScheduleController as TutorScheduleController;
use App\Http\Controllers\Tutor\EarningController as TutorEarningController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Student\StudentBookingController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Routes không yêu cầu đăng nhập
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Routes quên mật khẩu
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
    
    // Trang chủ cho khách
    Route::get('/', function () {
        return view('pages.home');
    })->name('home');
});

// Route cho đăng ký trở thành gia sư (public)
Route::get('/tutors/register', [TutorController::class, 'register'])->name('tutors.register');
Route::get('/tutors/become', [TutorController::class, 'create'])->name('tutors.create');

// Payment callback routes (không yêu cầu đăng nhập)
Route::get('/payment/vnpay/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');

// Các routes yêu cầu đăng nhập
    // Trang chủ cho người đã đăng nhập (áp dụng CheckRole)
    Route::get('/', function () {
        return view('pages.home');
    })->name('home');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('profile.password.update');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');

    // Payment routes
    Route::get('/payment/{booking}/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');

    // Tutor Routes (yêu cầu đăng nhập)
    Route::post('/tutors', [TutorController::class, 'store'])->name('tutors.store');
    Route::get('/tutors/{tutor}/pending', [TutorController::class, 'pending'])->name('tutors.pending');
    Route::get('/tutors/{tutor}/edit', [TutorController::class, 'edit'])->name('tutors.edit');
    Route::patch('/tutors/{tutor}', [TutorController::class, 'update'])->name('tutors.update');

    // Booking Routes
    Route::post('/tutors/{tutor}/book', [TutorController::class, 'book'])->name('tutors.book');
    Route::get('/bookings', [TutorController::class, 'bookings'])->name('tutors.bookings');
    
    // Public Routes với quyền truy cập được kiểm soát
    Route::get('/tutors', [TutorController::class, 'index'])->name('tutors.index');
    Route::get('/tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show');

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::get('/ai-advisor', [AiAdvisorController::class, 'index'])->name('ai-advisor');
    Route::post('/ai-advisor/chat', [AiAdvisorController::class, 'chat'])->name('ai-advisor.chat');
    Route::post('/ai-advisor/reset', [AiAdvisorController::class, 'resetConversation'])->name('ai-advisor.reset');

    // Trang tĩnh
    Route::get('/privacy-policy', function () {
        return view('pages.privacy-policy');
    })->name('privacy-policy');

    Route::get('/about-us', function () {
        return view('pages.about-us');
    })->name('about-us');

    Route::get('/contact', function () {
        return view('pages.contact');
    })->name('contact');

    Route::get('/faq', function () {
        return view('pages.faq');
    })->name('faq');

    Route::get('/guide', function () {
        return view('pages.guide');
    })->name('guide');

    Route::get('/terms', function () {
        return view('pages.terms');
    })->name('terms');

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {
    Route::get('/bookings', [StudentBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/tutors', [StudentBookingController::class, 'tutors'])->name('bookings.tutors');
    Route::get('/bookings/create/{tutor}', [StudentBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/{tutor}', [StudentBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [StudentBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/confirm-completion', [StudentBookingController::class, 'confirmCompletion'])->name('bookings.confirm-completion');
    Route::post('/bookings/{booking}/rate', [StudentBookingController::class, 'rateBooking'])->name('bookings.rate');
    
    // Quản lý lịch rảnh học sinh
    Route::get('/availability', [App\Http\Controllers\Student\AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/availability/create', [App\Http\Controllers\Student\AvailabilityController::class, 'create'])->name('availability.create');
    Route::post('/availability', [App\Http\Controllers\Student\AvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/availability/{id}/edit', [App\Http\Controllers\Student\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability/{id}', [App\Http\Controllers\Student\AvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{id}', [App\Http\Controllers\Student\AvailabilityController::class, 'destroy'])->name('availability.destroy');
    Route::post('/availability/quick-store', [App\Http\Controllers\Student\AvailabilityController::class, 'quickStore'])->name('availability.quick-store');
    
    // Review routes
    Route::get('/tutors/{tutor}/review', [ReviewController::class, 'create'])->name('tutors.review');
    Route::post('/tutors/{tutor}/review', [ReviewController::class, 'store'])->name('tutors.review.store');

    // Reschedule routes
    Route::get('/reschedules', [App\Http\Controllers\Student\RescheduleController::class, 'index'])->name('reschedules.index');
    Route::get('/reschedules/{rescheduleRequest}', [App\Http\Controllers\Student\RescheduleController::class, 'show'])->name('reschedules.show');
    Route::post('/reschedules/{rescheduleRequest}/respond', [App\Http\Controllers\Student\RescheduleController::class, 'respond'])->name('reschedules.respond');
});

// Tutor Routes
Route::middleware(['auth', 'tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [TutorProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [TutorProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/account', [TutorProfileController::class, 'updateAccount'])->name('profile.update-account');
    Route::put('/profile/password', [TutorProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/bookings', [TutorBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TutorBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [TutorBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('/bookings/{booking}/confirm-completion', [TutorBookingController::class, 'confirmCompletion'])->name('bookings.confirm-completion');
    Route::post('/bookings/{booking}/report-issue', [TutorBookingController::class, 'reportIssue'])->name('bookings.report-issue');
    
    // Lịch rảnh - Sử dụng AvailabilityController
    Route::get('/schedule', [App\Http\Controllers\Tutor\AvailabilityController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/quick', [App\Http\Controllers\Tutor\AvailabilityController::class, 'quickCreate'])->name('schedule.quick');
    Route::post('/schedule/quick-store', [App\Http\Controllers\Tutor\AvailabilityController::class, 'quickStore'])->name('schedule.quick-store');
    Route::get('/schedule/create', [App\Http\Controllers\Tutor\AvailabilityController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [App\Http\Controllers\Tutor\AvailabilityController::class, 'store'])->name('schedule.store');
    Route::get('/schedule/{availability}/edit', [App\Http\Controllers\Tutor\AvailabilityController::class, 'edit'])->name('schedule.edit');
    Route::put('/schedule/{availability}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{availability}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'destroy'])->name('schedule.destroy');
    
    // Lịch rảnh - Sử dụng route resource cho AvailabilityController mới
    Route::get('/availability', [App\Http\Controllers\Tutor\AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/availability/create', [App\Http\Controllers\Tutor\AvailabilityController::class, 'create'])->name('availability.create');
    Route::post('/availability', [App\Http\Controllers\Tutor\AvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/availability/{id}/edit', [App\Http\Controllers\Tutor\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability/{id}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{id}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'destroy'])->name('availability.destroy');
    Route::get('/availability/quick', [App\Http\Controllers\Tutor\AvailabilityController::class, 'quickCreate'])->name('availability.quick');
    Route::post('/availability/quick-store', [App\Http\Controllers\Tutor\AvailabilityController::class, 'quickStore'])->name('availability.quick-store');
    
    Route::get('/earnings', [TutorEarningController::class, 'index'])->name('earnings.index');

    // Reschedule routes
    Route::get('/bookings/{booking}/reschedule', [App\Http\Controllers\Tutor\RescheduleController::class, 'requestForm'])->name('bookings.reschedule');
    Route::post('/bookings/{booking}/reschedule', [App\Http\Controllers\Tutor\RescheduleController::class, 'store'])->name('bookings.reschedule.store');
    Route::get('/reschedules/{rescheduleRequest}', [App\Http\Controllers\Tutor\RescheduleController::class, 'show'])->name('reschedules.show');
    Route::post('/reschedules/{rescheduleRequest}/cancel', [App\Http\Controllers\Tutor\RescheduleController::class, 'cancel'])->name('reschedules.cancel');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('tutors', AdminTutorController::class);
    Route::post('tutors/{tutor}/approve', [AdminTutorController::class, 'approve'])->name('tutors.approve');
    Route::resource('subjects', AdminSubjectController::class);
    Route::resource('bookings', AdminBookingController::class);
    
    // Quản lý thu nhập và thanh toán cho gia sư
    Route::get('earnings', [App\Http\Controllers\Admin\TutorEarningController::class, 'index'])->name('earnings.index');
    Route::get('earnings/{earning}', [App\Http\Controllers\Admin\TutorEarningController::class, 'show'])->name('earnings.show');
    Route::get('earnings/{earning}/edit', [App\Http\Controllers\Admin\TutorEarningController::class, 'edit'])->name('earnings.edit');
    Route::patch('earnings/{earning}', [App\Http\Controllers\Admin\TutorEarningController::class, 'update'])->name('earnings.update');
    Route::get('tutors/{tutor}/earnings', [App\Http\Controllers\Admin\TutorEarningController::class, 'tutorEarnings'])->name('tutors.earnings');
    Route::post('earnings/process-completed', [App\Http\Controllers\Admin\TutorEarningController::class, 'processCompletedBookings'])->name('earnings.process-completed');
    Route::post('earnings/mark-as-processing', [App\Http\Controllers\Admin\TutorEarningController::class, 'markAsProcessing'])->name('earnings.mark-as-processing');
    Route::post('earnings/mark-as-completed', [App\Http\Controllers\Admin\TutorEarningController::class, 'markAsCompleted'])->name('earnings.mark-as-completed');

    // Thêm routes cho admin xử lý thanh toán và hoàn tiền
    Route::post('bookings/{booking}/confirm-payment', [AdminBookingController::class, 'confirmPayment'])->name('bookings.confirm-payment');
    Route::post('bookings/{booking}/process-refund', [AdminBookingController::class, 'processRefund'])->name('bookings.process-refund');
});
