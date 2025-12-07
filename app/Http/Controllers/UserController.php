<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->join('role', 'users.role_id', '=', 'role.role_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->select('users.*', 'role.role_name', 'unit.nama_unit')
            ->get();

        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $roles = DB::table('role')->get();
        $units = DB::table('unit')->get();
        return view('dashboard.users.create', compact('roles', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:role,role_id',
            'unit_id' => 'required|exists:unit,unit_id',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'unit_id' => $request->unit_id,
            'status' => 'Aktif',
        ]);

        return redirect('/dashboard/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = DB::table('users')->where('user_id', $id)->first();
        $roles = DB::table('role')->get();
        $units = DB::table('unit')->get();
        return view('dashboard.users.edit', compact('user', 'roles', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:role,role_id',
            'unit_id' => 'required|exists:unit,unit_id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'unit_id' => $request->unit_id,
        ];

        if ($request->password) {
            $data['password_hash'] = Hash::make($request->password);
        }

        DB::table('users')->where('user_id', $id)->update($data);

        return redirect('/dashboard/users')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('users')->where('user_id', $id)->delete();
        return redirect('/dashboard/users')->with('success', 'User berhasil dihapus');
    }
}
