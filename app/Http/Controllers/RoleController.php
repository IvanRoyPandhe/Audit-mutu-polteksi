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

    public function edit($id)
    {
        $role = DB::table('role')->where('role_id', $id)->first();
        $availablePermissions = [
            'dashboard' => 'Dashboard',
            'standar-mutu' => 'Standar Mutu',
            'kriteria' => 'Kriteria',
            'indikator-kinerja' => 'Indikator Kinerja',
            'penetapan' => 'Penetapan',
            'pelaksanaan' => 'Pelaksanaan',
            'evaluasi' => 'Evaluasi',
            'approval' => 'Approval',
            'laporan' => 'Laporan',
            'direktur-review' => 'Review Direktur',
            'spi-monitoring' => 'SPI Monitoring',
            'buku-kebijakan' => 'Buku Kebijakan',
            'users' => 'Users',
            'roles' => 'Roles',
            'units' => 'Units',
            'unit-auditors' => 'Unit Auditors'
        ];
        return view('dashboard.roles.edit', compact('role', 'availablePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        DB::table('role')->where('role_id', $id)->update([
            'permissions' => json_encode($request->permissions ?? []),
        ]);

        return redirect('/dashboard/roles')->with('success', 'Role permissions berhasil diupdate');
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
