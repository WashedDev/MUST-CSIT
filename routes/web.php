<?php

use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PageController;
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
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/book', [EventController::class, 'book'])->name('events.book');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

    Route::get('/profile', [PageController::class, 'profile'])->name('profile');

    Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
    Route::get('/elections/{election}', [ElectionController::class, 'show'])->name('elections.show');
    Route::post('/elections/{election}/vote', [ElectionController::class, 'vote'])->name('elections.vote');
    Route::get('/elections/{election}/results', [ElectionController::class, 'results'])->name('elections.results');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members.index');
        Route::get('/admin/members/{member}/edit', [AdminMemberController::class, 'edit'])->name('admin.members.edit');
        Route::put('/admin/members/{member}', [AdminMemberController::class, 'update'])->name('admin.members.update');
        Route::delete('/admin/members/{member}', [AdminMemberController::class, 'destroy'])->name('admin.members.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
