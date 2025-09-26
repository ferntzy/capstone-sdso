<?php

use Illuminate\Support\Facades\Route;



// authentication
Route::get('/', function () {
  return view('auth.login');
});


//admin dashboard
Route::get('/admin/dashboard', function () {
  return view('admin.dashboard');
});
Route::get('/admin/calendar', function () {
  return view('admin.calendardisplay');
});

//admin event request
Route::get('/admin/event-requests', function () {
  return view('admin.EventRequest.AllRequest');
});
Route::get('/admin/event-requests/pending', function () {
  return view('admin.EventRequest.PendingApproval');
});
Route::get('/admin/event-requests/approved-events', function () {
  return view('admin.EventRequest.ApprovedEvents');
});

//admin approvals
Route::get('/admin/approvals/pending', function () {
  return view('admin.approvals.pending');
});

Route::get('/admin/approvals/history', function () {
  return view('admin.approvals.history');
});

//admin e-signature
Route::get('/admin/esignatures/pending', function () {
  return view('admin.ESignature.pending');
});
Route::get('/admin/esignatures/completed', function () {
  return view('admin.ESignature.completed');
});

//admin organizations
Route::get('/admin/organizations', function () {
  return view('admin.organizations.organizations');
});

//admin reports
Route::get('/admin/reports/minutes', function () {
  return view('admin.reports.minutes');
});

//admin users
Route::get('/admin/users', function () {
  return view('admin.users.users');
});

Route::get('/admin/roles', function () {
  return view('admin.users.roles');
});

//admin account
Route::get('/admin/account', function () {
  return view('admin.profile.account');
});

//admin help
Route::get('/admin/help', function () {
  return view('admin.help.help');
});
