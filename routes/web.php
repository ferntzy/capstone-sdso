<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CalendarController;

Route::prefix('admin')->group(function () {
  Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
  Route::post('/calendar/events', [CalendarController::class, 'store'])->name('calendar.store');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login & Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout route
Route::get('/logout', function () {
  Auth::logout();
  session()->invalidate();
  session()->regenerateToken();

  return redirect('/login')->with('logout_success', true);
})->name('logout');

/*
|--------------------------------------------------------------------------
| Role-Based Dashboards & Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

  // Dashboard
  Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

  // Users Management
  Route::get('/users', [UserController::class, 'index'])->name('users.index');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
  Route::post('/users', [UserController::class, 'store'])->name('users.store');
  Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
  Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
  Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

  // Other Admin Pages
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

// Student Organization Routes
Route::middleware(['auth', 'role:Student_Organization'])->group(function () {
  Route::view('/student/dashboard', 'student.dashboard')->name('student.dashboard');
});

// SDSO Head Routes
Route::middleware(['auth', 'role:SDSO_Head'])->group(function () {
  Route::view('/sdso/dashboard', 'sdso.dashboard')->name('sdso.dashboard');
});

// Faculty Adviser Routes
Route::middleware(['auth', 'role:Faculty_Adviser'])->group(function () {
  Route::view('/faculty/dashboard', 'faculty.dashboard')->name('faculty.dashboard');
});

// VP SAS Routes
Route::middleware(['auth', 'role:VP_SAS'])->group(function () {
  Route::view('/vpsas/dashboard', 'vpsas.dashboard')->name('vpsas.dashboard');
});

// SAS Director Routes
Route::middleware(['auth', 'role:SAS_Director'])->group(function () {
  Route::view('/sas/dashboard', 'sas.dashboard')->name('sas.dashboard');
});

// BARGO Routes
Route::middleware(['auth', 'role:BARGO'])->group(function () {
  Route::view('/bargo/dashboard', 'bargo.dashboard')->name('bargo.dashboard');
});

/*
|--------------------------------------------------------------------------
| PDF Routes
|--------------------------------------------------------------------------
*/
Route::get('/pdf', [PdfController::class, 'generate']);
Route::get('/pdf-form', [PdfController::class, 'showForm']);
Route::get('/generate-pdf', [PdfController::class, 'generatePDF'])->name('pdf.generate');

Route::get('/generate-slsu-pdf', [PdfController::class, 'generatePDF']);
