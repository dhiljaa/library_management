<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController; // User Controller
use App\Http\Controllers\Admin\BookAdminController;
use App\Http\Controllers\Admin\LoanAdminController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController; // Admin Controller
use App\Http\Controllers\Staff\LoanStaffController;
use App\Http\Controllers\Admin\NotificationController;

// ==========================
// ✅ PUBLIC ROUTES
// ==========================

// 🔐 Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ==========================
// 🔒 PROTECTED ROUTES
// ==========================
Route::middleware('auth:sanctum')->group(function () {

    // 🔐 Authenticated User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // 📚 Books (User)
    Route::get('/public-books', [BookController::class, 'index']);
    Route::get('/books/top', [BookController::class, 'top']);
    Route::get('/books/category/{category}', [BookController::class, 'byCategory']);
    Route::get('/books/{id}', [BookController::class, 'show']);

    // 📂 Categories (User)
    Route::get('/categories', [CategoryController::class, 'index']);

    // 📝 Reviews
    Route::get('/books/{bookId}/reviews', [ReviewController::class, 'index']);
    Route::post('/books/{bookId}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // 📥 User Loans
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/history', [LoanController::class, 'history']);
    Route::put('/loans/{id}/return', [LoanController::class, 'return']);

    // 🛠️ Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // 📚 Manage Books
        Route::post('/books', [BookAdminController::class, 'store']);
        Route::put('/books/{id}', [BookAdminController::class, 'update']);
        Route::delete('/books/{id}', [BookAdminController::class, 'destroy']);

        // 🗂️ Manage Categories
        Route::apiResource('categories', AdminCategoryController::class);

        // 📋 Manage Loans
        Route::get('/loans', [LoanAdminController::class, 'index']);
        // Update status pinjaman (misal update status 'borrowed' ke 'returned')
        Route::put('/loans/{id}', [LoanAdminController::class, 'updateStatus']);

        // 👥 Manage Users
        Route::get('/users', [UserAdminController::class, 'index']);
        Route::put('/users/{id}/role', [UserAdminController::class, 'updateRole']);
        Route::delete('/users/{id}', [UserAdminController::class, 'destroy']);

        // 📊 Statistics
        Route::get('/statistik', [StatistikController::class, 'index']);
    });

    // 🔔 Notifications (Admin & Staff)
    Route::middleware(['role:admin,staff'])->prefix('admin')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/{id}', [NotificationController::class, 'show']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    });

    // 🧰 Staff Routes
    Route::middleware('role:staff')->prefix('staff')->group(function () {
        Route::get('/loans', [LoanStaffController::class, 'index']);
        Route::put('/loans/{id}', [LoanStaffController::class, 'update']);
    });
});
