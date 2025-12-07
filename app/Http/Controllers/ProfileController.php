<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = DB::table('users')
            ->join('role', 'users.role_id', '=', 'role.role_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->where('users.user_id', auth()->id())
            ->select('users.*', 'role.role_name', 'unit.nama_unit')
            ->first();

        return view('dashboard.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = DB::table('users')->where('user_id', auth()->id())->first();
        return view('dashboard.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',user_id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('user_id', auth()->id())->update($data);

        return redirect('/dashboard/profile')->with('success', 'Profile berhasil diupdate');
    }
}
