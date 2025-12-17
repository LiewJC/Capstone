<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingCartController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\StripePaymentController;
use App\Models\Admin;
use App\Models\User;

Route::middleware('admin.auth')->prefix('admin')->group(function () {

    Route::get('/adminlayout', function () {
        return view('admin.admin');
    });

    Route::get('/bookings', function () {
        return view('admin.manage-bookings');
    })->name('admin.manage-booking');
    Route::get('/bookings', [BookingController::class, 'index'])->name('admin.manage-booking');
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');


    Route::delete('/feedbacks/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedbacks.destroy');

    Route::patch('/payments/{id}/update-status', [PaymentController::class, 'updatePaymentStatus'])->name('admin.payments.updateStatus');


    Route::get('/discounts', function () {
        return view('admin.manage-discounts');
    })->name('admin.manage-discount');
    Route::get('/discounts', [DiscountController::class, 'index'])->name('admin.manage-discount');
    Route::get('/discounts/create', [DiscountController::class, 'create'])->name('admin.discounts.create');
    Route::post('/discounts/store', [DiscountController::class, 'store'])->name('admin.discounts.store');
    Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('admin.discounts.edit');
    Route::put('/discounts/{id}/update', [DiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('/discounts/{id}/delete', [DiscountController::class, 'destroy'])->name('admin.discounts.delete');

    Route::get('/services', function () {
        return view('admin.manage-services');
    })->name('admin.service');
    Route::get('/services', [ServiceController::class, 'index'])->name('admin.service');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');



    Route::get('/reports', [AdminController::class, 'showReport'])->name('admin.report');


    Route::get('/stores', function () {
        return view('admin.manage-stores');
    })->name('admin.manage-store');
    Route::get('/stores', [StoreController::class, 'index'])->name('admin.manage-store');
    Route::post('/stores', [StoreController::class, 'store'])->name('admin.stores.store');
    Route::delete('/stores/{id}', [StoreController::class, 'destroy'])->name('admin.stores.destroy');
    Route::get('/stores/add', [StoreController::class, 'create'])->name('admin.stores.create');
    Route::get('/stores/{id}/edit', [StoreController::class, 'edit'])->name('admin.stores.edit');
    Route::put('/stores/{id}', [StoreController::class, 'update'])->name('admin.stores.update');


    Route::get('/admins', [AdminController::class, 'index'])->name('admin.manage-admin');
    Route::get('/add', [AdminController::class, 'showAddForm'])->name('admin.add');
    Route::post('/add', [AdminController::class, 'addAdmin'])->name('admin.add.post');
    Route::patch('/admins/{id}/role', [AdminController::class, 'updateRole'])->name('admin.update.role');
    Route::delete('/admins/{id}', [AdminController::class, 'delete'])->name('admin.delete');

    Route::get('/profile', function () {
        $adminId = session('admin_id');
        $admin = Admin::find($adminId);

        return view('admin.profile', compact('admin'));
    })->name('admin.profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/profile/update-password', [AdminController::class, 'updatePassword'])->name('admin.profile.update-password');


    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('dashboard', [UserController::class, 'index'])->name('admin.dashboard');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleActiveStatus'])->name('admin.users.toggleStatus');

});


Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::post('/admin/logout', function () {
    session()->forget('admin_id');
    session()->flush();
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
})->name('admin.logout')->middleware('admin.auth');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp.post');


Route::get('/', function () {
    return view('index');
});


Route::get('/our-service',  [ServiceController::class, 'showServicePage']);

Route::get('/profile', function () {
    $userId = Auth::id();
    $user = User::find($userId);

    return view('profile', compact('user'));
})->name('profile');

Route::get('/booking-record', [BookingController::class, 'userBookingRecord'])->name('booking-record');
Route::get('/booking-cart', [BookingCartController::class, 'index'])->name('booking-cart');
Route::get('/booking', [StoreController::class, 'showAllStore'])->name('booking');
Route::post('/booking/select-store', [StoreController::class, 'selectStore'])->name('select.store');
Route::get('/select-service', [ServiceController::class, 'showServices'])->name('select.service');
Route::post('/cart/add', [BookingCartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{cart_id}', [BookingCartController::class, 'remove'])->name('cart.remove');
Route::get('/booking/select-date', [BookingController::class, 'showSelectDate'])->name('booking.select-date');

    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/payment', [PaymentController::class, 'show'])->name('payment.page');
    Route::post('/apply-discount', [PaymentController::class, 'applyDiscount'])->name('apply.discount');
Route::post('/stripe/checkout', [StripePaymentController::class, 'createCheckoutSession'])->name('stripe.checkout');
Route::get('/payment/success', [StripePaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [StripePaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/checkout/card', [PaymentController::class, 'handleCard'])->name('card.checkout');
Route::post('/checkout/ewallet', [PaymentController::class, 'handleEwallet'])->name('ewallet.checkout');
Route::post('/checkout/cash', [PaymentController::class, 'handleCash'])->name('cash.checkout');
Route::get('/payment/success1', [PaymentController::class, 'paypalSuccess'])->name('paypalpayment.success');
Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::post('/update-vehicle-info', [PaymentController::class, 'updateVehicleInfo'])->name('update.vehicle.info');

Route::get('/userlayout', function () {
        return view('user');
});
// Route::get('/payment-success', function () {
//     return view('payment_success');
// })->name('payment.success');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

