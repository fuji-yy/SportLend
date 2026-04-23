<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\FineSettingController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Peminjam\DashboardController as PeminjamDashboardController;
use App\Http\Controllers\Peminjam\ToolController as PeminjamToolController;
use App\Http\Controllers\Peminjam\BorrowingController as PeminjamBorrowingController;
use App\Http\Controllers\Peminjam\ReturnController as PeminjamReturnController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/peminjam/dashboard');
        }
    }
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::resource('users', UserController::class);

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Tools Management
    Route::resource('tools', ToolController::class);

    // Borrowings Management
    Route::get('/borrowings/create', [AdminBorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [AdminBorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [AdminBorrowingController::class, 'show'])->name('borrowings.show');
    Route::get('/borrowings/{borrowing}/edit', [AdminBorrowingController::class, 'edit'])->name('borrowings.edit');
    Route::put('/borrowings/{borrowing}', [AdminBorrowingController::class, 'update'])->name('borrowings.update');
    Route::patch('/borrowings/{borrowing}/status', [AdminBorrowingController::class, 'updateStatus'])->name('borrowings.updateStatus');
    Route::delete('/borrowings/{borrowing}', [AdminBorrowingController::class, 'destroy'])->name('borrowings.destroy');

    // Returns Management
    Route::resource('returns', AdminReturnController::class);

    // Status Management
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');

    // Fines Management
    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
    Route::get('/fines/{fine}', [FineController::class, 'show'])->name('fines.show');
    Route::patch('/fines/{fine}/mark-paid', [FineController::class, 'markPaid'])->name('fines.markPaid');
    Route::patch('/fines/{fine}/waive', [FineController::class, 'waive'])->name('fines.waive');
    Route::get('/fines-settings', [FineSettingController::class, 'edit'])->name('fines.settings.edit');
    Route::patch('/fines-settings', [FineSettingController::class, 'update'])->name('fines.settings.update');
});

// Peminjam Routes
Route::middleware(['auth', 'role:peminjam'])->prefix('/peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', [PeminjamDashboardController::class, 'index'])->name('dashboard');

    // Tools
    Route::get('/tools', [PeminjamToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/{tool}', [PeminjamToolController::class, 'show'])->name('tools.show');
    Route::get('/tools/search', [PeminjamToolController::class, 'search'])->name('tools.search');

    // Borrowing
    Route::get('/borrowings', [PeminjamBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [PeminjamBorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [PeminjamBorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [PeminjamBorrowingController::class, 'show'])->name('borrowings.show');
    Route::patch('/borrowings/{borrowing}/cancel', [PeminjamBorrowingController::class, 'cancel'])->name('borrowings.cancel');

    // Return
    Route::get('/returns', [PeminjamReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create', [PeminjamReturnController::class, 'create'])->name('returns.create');
    Route::get('/returns/{borrowing}', [PeminjamReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{borrowing}', [PeminjamReturnController::class, 'store'])->name('returns.store');
});
