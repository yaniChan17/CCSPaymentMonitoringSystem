<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\FeeScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TreasurerDashboardController;
use App\Http\Controllers\Treasurer\PaymentController as TreasurerPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect authenticated users to their role-based dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Role-based dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isTreasurer()) {
        return redirect()->route('treasurer.dashboard');
    } elseif ($user->isStudent()) {
        return redirect()->route('student.dashboard');
    }
    
    abort(403, 'No dashboard assigned to your role.');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Payment Management
    Route::resource('payments', AdminPaymentController::class)->except(['create', 'store']);
    Route::get('/payments-export', [AdminPaymentController::class, 'export'])->name('payments.export');
    
    // Fee Schedules
    Route::resource('fee-schedules', FeeScheduleController::class);
    Route::post('fee-schedules/{feeSchedule}/activate', [FeeScheduleController::class, 'activate'])->name('fee-schedules.activate');
    Route::post('fee-schedules/{feeSchedule}/close', [FeeScheduleController::class, 'close'])->name('fee-schedules.close');
    
    // Announcements
    Route::resource('announcements', AnnouncementController::class)->except(['show', 'edit', 'update']);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
    Route::get('/reports/export-payments', [ReportController::class, 'exportPayments'])->name('reports.export-payments');
    Route::get('/reports/export-students', [ReportController::class, 'exportStudents'])->name('reports.export-students');
    Route::get('/reports/export-summary', [ReportController::class, 'exportSummary'])->name('reports.export-summary');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

// Treasurer Routes
Route::middleware(['auth', 'treasurer'])->prefix('treasurer')->name('treasurer.')->group(function () {
    Route::get('/dashboard', [TreasurerDashboardController::class, 'index'])->name('dashboard');
    
    // Payment Management
    Route::resource('payments', TreasurerPaymentController::class);
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// Notifications (all roles)
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// Profile Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
