<?php

use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\AdminMerchController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MerchController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/auth/google', [OAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/complete', [OAuthController::class, 'showCompleteForm'])->name('auth.complete');
    Route::post('/auth/complete', [OAuthController::class, 'completeRegistration'])->name('auth.complete.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/payment', [PaymentController::class, 'showForm'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/payment/tan/{payment}', [PaymentController::class, 'showTan'])->name('payment.tan');
    Route::post('/payment/tan/{payment}/check', [PaymentController::class, 'checkTanStatus'])->name('payment.tan.check');

    Route::middleware('membership')->group(function () {
        Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
        Route::get('/events/{event}/details', [EventController::class, 'details'])->name('events.details');
        Route::get('/events/{event}/attendees', [EventController::class, 'exportAttendees'])->name('events.attendees');
        Route::post('/events/{event}/book', [EventController::class, 'book'])->name('events.book');
        Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');

        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::get('/articles/{article}/details', [ArticleController::class, 'details'])->name('articles.details');

        Route::get('/profile', [PageController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [PageController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [PageController::class, 'updateProfile'])->name('profile.update');

        Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
        Route::get('/elections/{election}', [ElectionController::class, 'show'])->name('elections.show');
        Route::post('/elections/{election}/confirm', [ElectionController::class, 'confirmVote'])->name('elections.confirm');
        Route::post('/elections/{election}/vote', [ElectionController::class, 'vote'])->name('elections.vote');
        Route::get('/elections/{election}/results', [ElectionController::class, 'results'])->name('elections.results');
        Route::get('/elections/{election}/results/csv', [ElectionController::class, 'exportCsv'])->name('elections.results.csv');

        Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
        Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');

        Route::get('/search', [SearchController::class, 'search'])->name('search');

        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');

        Route::get('/merch', [MerchController::class, 'index'])->name('merch.index');
        Route::get('/merch/cart', [MerchController::class, 'cart'])->name('merch.cart');
        Route::post('/merch/cart/add/{merchItem}', [MerchController::class, 'addToCart'])->name('merch.cart.add');
        Route::post('/merch/cart/add-json/{merchItem}', [MerchController::class, 'addToCartJson'])->name('merch.cart.add-json');
        Route::post('/merch/cart/update/{merchItem}', [MerchController::class, 'updateCart'])->name('merch.cart.update');
        Route::post('/merch/cart/remove/{merchItem}', [MerchController::class, 'removeFromCart'])->name('merch.cart.remove');
        Route::post('/merch/cart/checkout', [MerchController::class, 'checkout'])->name('merch.checkout');
        Route::get('/merch/cart/success', [MerchController::class, 'checkoutSuccess'])->name('merch.checkout.success');
        Route::get('/merch/payment/tan', [MerchController::class, 'showTan'])->name('merch.payment.tan');
        Route::post('/merch/payment/tan/check', [MerchController::class, 'checkTanStatus'])->name('merch.payment.tan.check');
        Route::get('/merch/{merchItem}', [MerchController::class, 'show'])->name('merch.show');
        Route::get('/merch/{merchItem}/details', [MerchController::class, 'details'])->name('merch.details');

        Route::middleware('admin')->group(function () {
            Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members.index');
            Route::get('/admin/members/{member}/edit', [AdminMemberController::class, 'edit'])->name('admin.members.edit');
            Route::put('/admin/members/{member}', [AdminMemberController::class, 'update'])->name('admin.members.update');
            Route::delete('/admin/members/{member}', [AdminMemberController::class, 'destroy'])->name('admin.members.destroy');

            Route::get('/admin/events', [AdminEventController::class, 'index'])->name('admin.events.index');
            Route::get('/admin/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');
            Route::post('/admin/events', [AdminEventController::class, 'store'])->name('admin.events.store');
            Route::get('/admin/events/{event}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
            Route::put('/admin/events/{event}', [AdminEventController::class, 'update'])->name('admin.events.update');
            Route::delete('/admin/events/{event}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');

            Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
            Route::put('/admin/payments/{payment}', [AdminPaymentController::class, 'markPaid'])->name('admin.payments.mark-paid');

            Route::get('/admin/merch', [AdminMerchController::class, 'index'])->name('admin.merch.index');
            Route::get('/admin/merch/create', [AdminMerchController::class, 'create'])->name('admin.merch.create');
            Route::post('/admin/merch', [AdminMerchController::class, 'store'])->name('admin.merch.store');
            Route::get('/admin/merch/{merchItem}/edit', [AdminMerchController::class, 'edit'])->name('admin.merch.edit');
            Route::put('/admin/merch/{merchItem}', [AdminMerchController::class, 'update'])->name('admin.merch.update');
            Route::delete('/admin/merch/{merchItem}', [AdminMerchController::class, 'destroy'])->name('admin.merch.destroy');
        });
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::post('/layout/toggle', [PageController::class, 'toggleLayout'])->name('layout.toggle');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
