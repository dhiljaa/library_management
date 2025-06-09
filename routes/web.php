<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\BookAdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\LoanAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Staff\LoanStaffController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Hanya untuk user yang login
Route::middleware('auth')->group(function () {

    /**
     * =======================
     * Admin Routes
     * =======================
     */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('profile/edit', [ProfileAdminController::class, 'edit'])->name('profile.edit');
        Route::put('profile/update', [ProfileAdminController::class, 'update'])->name('profile.update');

        Route::get('/dashboard', [StatistikController::class, 'index'])->name('dashboard');

        // Buku dan Kategori
        Route::resource('/books', BookAdminController::class);
        Route::get('/books/popular', [BookAdminController::class, 'popular'])->name('books.popular');

        Route::resource('/categories', AdminCategoryController::class);

        // Peminjaman
        Route::get('/loans', [LoanAdminController::class, 'index'])->name('loans.index');
        Route::get('/loans/{id}', [LoanAdminController::class, 'show'])->name('loans.show');
        Route::put('/loans/{id}', [LoanAdminController::class, 'updateStatus'])->name('loans.updateStatus');
        Route::delete('/loans/{id}', [LoanAdminController::class, 'destroy'])->name('loans.destroy');

        // 🔽 Tambahan fitur Export & Advanced Search 🔽
        Route::get('/loans/export/pdf', [LoanAdminController::class, 'exportPDF'])->name('loans.export.pdf');
        Route::get('/loans/search', [LoanAdminController::class, 'search'])->name('loans.search');

        // User Management
        Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
        Route::get('/users/{id}/edit', [UserAdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserAdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserAdminController::class, 'destroy'])->name('users.destroy');

        // Statistik
        Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    });

    /**
     * =======================
     * Admin & Staff Notification Routes
     * =======================
     */
    Route::middleware('role:admin,staff')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    /**
     * =======================
     * Staff Routes
     * =======================
     */
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/loans', [LoanStaffController::class, 'index'])->name('loans.index');
        Route::put('/loans/{id}', [LoanStaffController::class, 'update'])->name('loans.update');
    });
});
