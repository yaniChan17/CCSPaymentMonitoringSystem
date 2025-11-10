<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TreasurerDashboardController;
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
    Route::resource('fee-schedules', App\Http\Controllers\Admin\FeeScheduleController::class);
    Route::post('fee-schedules/{feeSchedule}/activate', [App\Http\Controllers\Admin\FeeScheduleController::class, 'activate'])->name('fee-schedules.activate');
    Route::post('fee-schedules/{feeSchedule}/close', [App\Http\Controllers\Admin\FeeScheduleController::class, 'close'])->name('fee-schedules.close');
    
    // Announcements
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class)->except(['show', 'edit', 'update']);
    
    // Blocks Management
    Route::resource('blocks', App\Http\Controllers\Admin\BlockController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
    Route::get('/reports/export-payments', [ReportController::class, 'exportPayments'])->name('reports.export-payments');
    Route::get('/reports/export-students', [ReportController::class, 'exportStudents'])->name('reports.export-students');
    Route::get('/reports/export-summary', [ReportController::class, 'exportSummary'])->name('reports.export-summary');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
});

// Treasurer Routes
Route::middleware(['auth', 'treasurer'])->prefix('treasurer')->name('treasurer.')->group(function () {
    Route::get('/dashboard', [TreasurerDashboardController::class, 'index'])->name('dashboard');
    
    // Payment Management (Direct Posting - No Approval)
    Route::resource('payments', App\Http\Controllers\Treasurer\PaymentController::class);
    
    // Students in My Block
    Route::get('/students', [App\Http\Controllers\Treasurer\StudentController::class, 'index'])->name('students.index');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Help Center
    Route::get('/help', [App\Http\Controllers\Student\HelpController::class, 'index'])->name('help.index');
    Route::get('/help/contact', [App\Http\Controllers\Student\HelpController::class, 'contact'])->name('help.contact');
});

// Profile Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    
    // Account Settings (separate from profile)
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings.edit');
    Route::patch('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password.update');
    Route::delete('/settings/account', [ProfileController::class, 'destroy'])->name('settings.account.destroy');
    
    // Notifications (all roles)
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

require __DIR__.'/auth.php';
