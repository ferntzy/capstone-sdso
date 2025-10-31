<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\StudentEventController;
use App\Http\Controllers\CalendarControlller;
// ============================
// AUTH ROUTES
// ============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/logout', function () {
  Auth::logout();
  session()->invalidate();
  session()->regenerateToken();
  return redirect('/login')->with('logout_success', true);
})->name('logout');

// ============================
// ADMIN ROUTES
// ============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
  Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

  Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
  });


  Route::get('/logs', [App\Http\Controllers\UserLogController::class, 'index'])->name('admin.logs');

  Route::view('/calendar', 'admin.calendardisplay');
  Route::view('/event-requests', 'admin.EventRequest.AllRequest');
  Route::view('/event-requests/pending', 'admin.EventRequest.PendingApproval');
  Route::view('/event-requests/approved-events', 'admin.EventRequest.ApprovedEvents');
  Route::view('/approvals/pending', 'admin.approvals.pending');
  Route::view('/approvals/history', 'admin.approvals.history');
  Route::view('/esignatures/pending', 'admin.ESignature.pending');
  Route::view('/esignatures/completed', 'admin.ESignature.completed');
  Route::view('/organizations', 'admin.organizations.organizations');
  Route::view('/reports/minutes', 'admin.reports.minutes');
  Route::view('/roles', 'admin.users.roles');
  Route::view('/account', 'admin.profile.account');
  Route::view('/help', 'admin.help.help');
});

// ============================
// STUDENT ORGANIZATION ROUTES
// ============================
Route::middleware(['auth', 'role:Student_Organization'])->prefix('student')->group(function () {
  Route::get('/dashboard', [App\Http\Controllers\StudentDashboardController::class, 'index'])->name('student.dashboard');
  Route::get('/events', [StudentEventController::class, 'index'])->name('student.event.index');
  Route::view('/calendar', 'student.calendar')->name('student.calendar');
  Route::view('/profile', 'student.profile')->name('student.profile');

  // Permit routes
  Route::get('/permit/form', [PermitController::class, 'showForm'])->name('permit.form');
  Route::post('/permit/generate', [PermitController::class, 'generate'])->name('permit.generate');
  Route::get('/permit/tracking', [PermitController::class, 'track'])->name('student.permit.tracking');

  // ✅ Added route to view individual permit PDF
  Route::get('/permit/view/{permit}', [PermitController::class, 'view'])->name('student.permit.view');
});
// ============================
// OTHER ROLES
// ============================
Route::middleware(['auth', 'role:SDSO_Head'])->group(function () {
  Route::view('/sdso/dashboard', 'sdso.dashboard')->name('sdso.dashboard');
});

Route::middleware(['auth', 'role:Faculty_Adviser'])->group(function () {
  Route::view('/adviser/dashboard', 'adviser.dashboard')->name('adviser.dashboard');
});

Route::middleware(['auth', 'role:VP_SAS'])->group(function () {
  Route::view('/vpsas/dashboard', 'vpsas.dashboard')->name('vpsas.dashboard');
});

Route::middleware(['auth', 'role:SAS_Director'])->group(function () {
  Route::view('/sas/dashboard', 'sas.dashboard')->name('sas.dashboard');
});

Route::middleware(['auth', 'role:BARGO'])->group(function () {
  Route::view('/bargo/dashboard', 'bargo.dashboard')->name('bargo.dashboard');
});

// ============================
// CALENDAR API ROUTES
// ============================
Route::prefix('admin')->group(function () {
  Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
  Route::post('/calendar/events', [CalendarController::class, 'store'])->name('calendar.store');
});
