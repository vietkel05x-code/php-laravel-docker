<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// routes/web.php

// Public: Danh sách và chi tiết khóa học
Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [\App\Http\Controllers\CourseController::class, 'show'])
    ->name('courses.show');

// Shopping Cart
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{course}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{course}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Checkout & Orders
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/direct/{course}', [\App\Http\Controllers\CheckoutController::class, 'direct'])->name('checkout.direct');
    Route::post('/checkout/direct/{course}', [\App\Http\Controllers\CheckoutController::class, 'processDirect'])->name('checkout.process-direct');
    Route::post('/checkout/apply-coupon', [\App\Http\Controllers\CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::post('/checkout/remove-coupon', [\App\Http\Controllers\CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
    
    // Payment
    Route::get('/payment/gateway/{order}/{method}', [\App\Http\Controllers\PaymentController::class, 'gateway'])->name('payment.gateway');
    Route::post('/payment/success/{order}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel')->where('order', '[0-9]+');
    Route::get('/payment/bank-transfer/{order}', [\App\Http\Controllers\PaymentController::class, 'bankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/confirm-bank-transfer/{order}', [\App\Http\Controllers\PaymentController::class, 'confirmBankTransfer'])->name('payment.confirm-bank-transfer');
    
    // Payment Callbacks (Public routes - không cần auth vì MoMo/VNPay sẽ gọi)
    Route::get('/payment/momo/return', [\App\Http\Controllers\PaymentCallbackController::class, 'momoReturn'])->name('payment.momo.return');
    Route::match(['GET', 'POST'], '/payment/momo/notify', [\App\Http\Controllers\PaymentCallbackController::class, 'momoNotify'])->name('payment.momo.notify');
    Route::get('/payment/vnpay/return', [\App\Http\Controllers\PaymentCallbackController::class, 'vnpayReturn'])->name('payment.vnpay.return');
    
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    
    // Student Dashboard
    Route::get('/my-courses', [\App\Http\Controllers\StudentController::class, 'myCourses'])->name('student.courses');
    Route::get('/learn/{course:slug}', [\App\Http\Controllers\StudentController::class, 'learn'])->name('student.learn');
    Route::get('/learn/{course:slug}/lesson/{lesson}', [\App\Http\Controllers\StudentController::class, 'lesson'])->name('student.lesson');
    Route::post('/lesson/{lesson}/complete', [\App\Http\Controllers\StudentController::class, 'completeLesson'])->name('student.complete-lesson');
    
    // Reviews
    Route::post('/courses/{course}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/count', [\App\Http\Controllers\NotificationController::class, 'count'])->name('notifications.count');
});

// Admin - Đăng nhập riêng
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Auth Routes (không cần middleware)
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    // Admin Protected Routes - Yêu cầu đăng nhập và role admin
    // Sử dụng middleware 'admin' thay vì 'auth' để tránh redirect về login public
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Courses
    Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [\App\Http\Controllers\Admin\CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [\App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update');
    
    // Sections
    Route::post('/courses/{course}/sections', [\App\Http\Controllers\Admin\SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('sections.destroy');
    
    // Lessons
    Route::post('/sections/{section}/lessons', [\App\Http\Controllers\Admin\LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'destroy'])->name('lessons.destroy');
    
    // YouTube API - Get video duration
    Route::post('/api/youtube/duration', [\App\Http\Controllers\Admin\LessonController::class, 'getYouTubeDuration'])->name('api.youtube.duration');
    
    // API - Get sidebar content (for updating after lesson completion)
    Route::get('/api/courses/{course}/sidebar', [\App\Http\Controllers\StudentController::class, 'getSidebar'])->name('api.courses.sidebar');
    
    // Reviews
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    // Categories
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Coupons
    Route::get('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Statistics
    Route::get('/statistics/revenue', [\App\Http\Controllers\Admin\StatisticsController::class, 'revenue'])->name('statistics.revenue');
    Route::get('/statistics/courses', [\App\Http\Controllers\Admin\StatisticsController::class, 'courses'])->name('statistics.courses');
    Route::get('/statistics/students', [\App\Http\Controllers\Admin\StatisticsController::class, 'students'])->name('statistics.students');
    });
});
