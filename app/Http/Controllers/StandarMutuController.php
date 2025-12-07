<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StandarMutuController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa mengakses standar mutu');
        }

        $standar = DB::table('standar_mutu')
            ->join('users as pembuat', 'standar_mutu.dibuat_oleh', '=', 'pembuat.user_id')
            ->leftJoin('users as penyetuju', 'standar_mutu.disetujui_oleh', '=', 'penyetuju.user_id')
            ->select('standar_mutu.*', 'pembuat.name as pembuat', 'penyetuju.name as penyetuju')
            ->orderBy('standar_mutu.tanggal_dibuat', 'desc')
            ->get();

        return view('dashboard.standar-mutu.index', compact('standar'));
    }

    public function create()
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa membuat standar mutu');
        }
        return view('dashboard.standar-mutu.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa membuat standar mutu');
        }

        $request->validate([
            'kode_standar' => 'required|unique:standar_mutu,kode_standar|max:20',
            'nama_standar' => 'required|max:150',
            'deskripsi' => 'nullable',
        ]);

        DB::table('standar_mutu')->insert([
            'kode_standar' => $request->kode_standar,
            'nama_standar' => $request->nama_standar,
            'deskripsi' => $request->deskripsi,
            'dibuat_oleh' => auth()->id(),
            'tanggal_dibuat' => now(),
            'status' => 'Draft',
        ]);

        return redirect('/dashboard/standar-mutu')->with('success', 'Standar mutu berhasil dibuat');
    }

    public function edit($id)
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa edit standar mutu');
        }

        $standar = DB::table('standar_mutu')->where('standar_id', $id)->first();
        return view('dashboard.standar-mutu.edit', compact('standar'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa edit standar mutu');
        }

        $request->validate([
            'kode_standar' => 'required|max:20|unique:standar_mutu,kode_standar,'.$id.',standar_id',
            'nama_standar' => 'required|max:150',
            'deskripsi' => 'nullable',
        ]);

        DB::table('standar_mutu')->where('standar_id', $id)->update([
            'kode_standar' => $request->kode_standar,
            'nama_standar' => $request->nama_standar,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/dashboard/standar-mutu')->with('success', 'Standar mutu berhasil diupdate');
    }

    public function destroy($id)
    {
        if (auth()->user()->role_id != 1) {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa hapus standar mutu');
        }

        DB::table('standar_mutu')->where('standar_id', $id)->delete();
        return redirect('/dashboard/standar-mutu')->with('success', 'Standar mutu berhasil dihapus');
    }
}
