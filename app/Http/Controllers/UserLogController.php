<?php

namespace App\Http\Controllers;

use App\Models\UserLog;

class UserLogController extends Controller
{
  public function index()
  {
    $logs = \App\Models\UserLog::with('user')->latest()->get();
    return view('admin.logs', compact('logs'));
  }
}
