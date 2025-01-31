<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function edit()
{
    $user = auth()->user();
    return view('account.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'username' => 'required|unique:users,username,' . $user->id,
        'current_password' => 'required',
        'new_password' => 'nullable|min:6|confirmed'
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
    }

    // Gunakan update() untuk memastikan data tersimpan
    $updateData = ['username' => $request->username];
    
    if ($request->new_password) {
        $updateData['password'] = Hash::make($request->new_password);
    }

    $user->update($updateData);

    return redirect()->route('dashboard')->with('success', 'Akun berhasil diperbarui');
}
}
