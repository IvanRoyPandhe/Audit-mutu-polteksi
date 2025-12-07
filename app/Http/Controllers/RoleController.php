<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::table('role')->get();
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('dashboard.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|max:50',
            'description' => 'nullable',
        ]);

        DB::table('role')->insert([
            'role_name' => $request->role_name,
            'description' => $request->description,
        ]);

        return redirect('/dashboard/roles')->with('success', 'Role berhasil ditambahkan');
    }

    public function destroy($id)
    {
        DB::table('role')->where('role_id', $id)->delete();
        return redirect('/dashboard/roles')->with('success', 'Role berhasil dihapus');
    }
}
