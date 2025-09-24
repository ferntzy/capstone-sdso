<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // if you use hashing
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
  // Show login page
  public function showLoginForm()
  {
    return view('auth.login');
  }

  // Handle login attempt
  public function login(Request $request)
  {
    $request->validate([
      'email-username' => 'required|string',
      'password' => 'required|string',
    ]);

    $login = $request->input('email-username');
    $password = $request->input('password');

    // Check if login is via email or username
    $user = DB::table('user')
      ->where('email', $login)
      ->orWhere('username', $login)
      ->first();

    if ($user) {
      // 🔹 If passwords are hashed (recommended):
      // if (Hash::check($password, $user->password)) {

      // 🔹 If passwords are stored as plain text (not recommended):
      if ($password === $user->password) {
        // Save user in session
        Session::put('user_id', $user->user_id);
        Session::put('username', $user->username);
        Session::put('account_role', $user->account_role);
        Session::put('logged_in', true);

        return redirect()->intended('/dashboard'); // redirect after login
      }
    }

    return back()->withErrors(['login' => 'Invalid credentials.']);
  }

  // Logout
  public function logout(Request $request)
  {
    Session::flush();
    return redirect('/login')->with('success', 'Logged out successfully');
  }
}
