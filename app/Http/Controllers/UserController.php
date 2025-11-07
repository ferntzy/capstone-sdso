<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
  public function index()
  {
    $users = User::all();
    return view('admin.users.index', compact('users'));
  }

  public function create()
  {
    return view('admin.users.create');
  }

  public function store(Request $request)
  {
    try{
        $validated = $request->validate([
          'username' => 'required|unique:users,username',
          'email' => 'required|email|unique:users,email',
          'password' => 'required|min:6',
          'account_role' => 'required',
        ]);

        User::create([
          'username' => $validated['username'],
          'email' => $validated['email'],
          'password' => Hash::make($validated['password']),
          'account_role' => $validated['account_role'],
        ]);

      return response()->json(['success' => true, 'message' => 'User created successfully']);

    }catch(Exception $e){
      return response()->json(['error' => $e->getMessage()],400);
    }

    // return redirect()->route('users.index')->with('success', 'User created successfully!');
  }

  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  public function update(Request $request, User $user)
  {
    $request->validate([
      'username' => 'required|unique:users,username,' . $user->user_id . ',user_id',
      'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
      'account_role' => 'required',
    ]);

    $data = [
      'username' => $request->username,
      'email' => $request->email,
      'account_role' => $request->account_role,
    ];

    if ($request->filled('password')) {
      $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('users.index')->with('success', 'User updated successfully!');
  }

  public function destroy(User $user)
  {
    $user->delete();
    return response()->json([
      'success' => true,
      'user_id' => $user->user_id,
      'message' => 'User deleted successfully!',
    ]);
  }
}
