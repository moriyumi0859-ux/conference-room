<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConferenceRoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ConferenceRoomController as AdminConferenceRoomController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;

// トップページ
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('conference-rooms.index');
    }
    return redirect()->route('login');
});

// 利用者：ログインが必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/conference-rooms', [ConferenceRoomController::class, 'index'])->name('conference-rooms.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// 利用者認証（Breezeが自動生成）
require __DIR__.'/auth.php';

// 管理者ルート
Route::prefix('admin')->name('admin.')->group(function () {
    // ログイン不要
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // ログイン済み
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/conference-rooms', AdminConferenceRoomController::class);
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::delete('/reservations/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');
    });
});