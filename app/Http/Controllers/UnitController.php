<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        $units = DB::table('unit')->get();
        return view('dashboard.units.index', compact('units'));
    }

    public function create()
    {
        return view('dashboard.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|max:100',
            'tipe_unit' => 'required|max:50',
            'pimpinan' => 'required|max:100',
        ]);

        DB::table('unit')->insert([
            'nama_unit' => $request->nama_unit,
            'tipe_unit' => $request->tipe_unit,
            'pimpinan' => $request->pimpinan,
        ]);

        return redirect('/dashboard/units')->with('success', 'Unit berhasil ditambahkan');
    }

    public function edit($id)
    {
        $unit = DB::table('unit')->where('unit_id', $id)->first();
        return view('dashboard.units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_unit' => 'required|max:100',
            'tipe_unit' => 'required|max:50',
            'pimpinan' => 'required|max:100',
        ]);

        DB::table('unit')->where('unit_id', $id)->update([
            'nama_unit' => $request->nama_unit,
            'tipe_unit' => $request->tipe_unit,
            'pimpinan' => $request->pimpinan,
        ]);

        return redirect('/dashboard/units')->with('success', 'Unit berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('unit')->where('unit_id', $id)->delete();
        return redirect('/dashboard/units')->with('success', 'Unit berhasil dihapus');
    }
}
