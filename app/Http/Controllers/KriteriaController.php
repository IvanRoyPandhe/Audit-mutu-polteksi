<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('kriteria')
            ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
            ->join('users as pembuat', 'kriteria.dibuat_oleh', '=', 'pembuat.user_id')
            ->leftJoin('users as penyetuju', 'kriteria.disetujui_oleh', '=', 'penyetuju.user_id')
            ->select('kriteria.*', 'standar_mutu.nama_standar', 'standar_mutu.kode_standar', 'pembuat.name as pembuat', 'penyetuju.name as penyetuju');

        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 4) {
            $query->where('kriteria.dibuat_oleh', auth()->id());
        }

        // Filter untuk admin dan direktur
        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4) {
            if ($request->unit_id) {
                $query->join('users as u', 'kriteria.dibuat_oleh', '=', 'u.user_id')
                      ->where('u.unit_id', $request->unit_id);
            }
            if ($request->status) {
                $query->where('kriteria.status', $request->status);
            }
        }

        // Filter untuk semua role
        if ($request->tahun) {
            $query->where('kriteria.tahun_berlaku', $request->tahun);
        }
        if ($request->standar_id) {
            $query->where('kriteria.standar_id', $request->standar_id);
        }

        $kriteria = $query->orderBy('kriteria.tanggal_dibuat', 'desc')->get();

        $standar_list = DB::table('standar_mutu')->get();
        $tahun_list = DB::table('kriteria')->distinct()->pluck('tahun_berlaku')->sort()->values();
        $units = DB::table('unit')->get();

        return view('dashboard.kriteria.index', compact('kriteria', 'standar_list', 'tahun_list', 'units'));
    }

    public function resubmit($id)
    {
        $kriteria = DB::table('kriteria')->where('kriteria_id', $id)->first();
        
        if ($kriteria->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/kriteria')->with('error', 'Anda tidak bisa ajukan kembali data ini');
        }

        DB::table('kriteria')->where('kriteria_id', $id)->update(['status' => 'Draft']);
        return redirect('/dashboard/kriteria')->with('success', 'Berhasil diajukan kembali');
    }

    public function create()
    {
        $standar = DB::table('standar_mutu')->where('status', 'Disetujui')->get();
        return view('dashboard.kriteria.create', compact('standar'));
    }

    public function show($id)
    {
        $kriteria = DB::table('kriteria')
            ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
            ->join('users as pembuat', 'kriteria.dibuat_oleh', '=', 'pembuat.user_id')
            ->leftJoin('users as penyetuju', 'kriteria.disetujui_oleh', '=', 'penyetuju.user_id')
            ->where('kriteria.kriteria_id', $id)
            ->select('kriteria.*', 'standar_mutu.nama_standar', 'standar_mutu.kode_standar', 'pembuat.name as pembuat', 'penyetuju.name as penyetuju')
            ->first();

        return view('dashboard.kriteria.show', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'standar_id' => 'required|exists:standar_mutu,standar_id',
            'kode_kriteria' => 'required|unique:kriteria,kode_kriteria|max:20',
            'nama_kriteria' => 'required|max:150',
            'deskripsi' => 'nullable',
            'tahun_berlaku' => 'required|integer',
        ]);

        DB::table('kriteria')->insert([
            'standar_id' => $request->standar_id,
            'kode_kriteria' => $request->kode_kriteria,
            'nama_kriteria' => $request->nama_kriteria,
            'deskripsi' => $request->deskripsi,
            'tahun_berlaku' => $request->tahun_berlaku,
            'dibuat_oleh' => auth()->id(),
            'tanggal_dibuat' => now(),
            'status' => 'Draft',
        ]);

        return redirect('/dashboard/kriteria')->with('success', 'Kriteria berhasil dibuat');
    }

    public function edit($id)
    {
        $kriteria = DB::table('kriteria')->where('kriteria_id', $id)->first();
        
        if ($kriteria->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/kriteria')->with('error', 'Anda tidak bisa edit data ini');
        }

        $standar = DB::table('standar_mutu')->where('status', 'Disetujui')->get();
        return view('dashboard.kriteria.edit', compact('kriteria', 'standar'));
    }

    public function update(Request $request, $id)
    {
        $kriteria = DB::table('kriteria')->where('kriteria_id', $id)->first();
        
        if ($kriteria->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/kriteria')->with('error', 'Anda tidak bisa edit data ini');
        }

        $request->validate([
            'standar_id' => 'required|exists:standar_mutu,standar_id',
            'kode_kriteria' => 'required|max:20|unique:kriteria,kode_kriteria,'.$id.',kriteria_id',
            'nama_kriteria' => 'required|max:150',
            'deskripsi' => 'nullable',
            'tahun_berlaku' => 'required|integer',
        ]);

        DB::table('kriteria')->where('kriteria_id', $id)->update([
            'standar_id' => $request->standar_id,
            'kode_kriteria' => $request->kode_kriteria,
            'nama_kriteria' => $request->nama_kriteria,
            'deskripsi' => $request->deskripsi,
            'tahun_berlaku' => $request->tahun_berlaku,
        ]);

        return redirect('/dashboard/kriteria')->with('success', 'Kriteria berhasil diupdate');
    }

    public function destroy($id)
    {
        $kriteria = DB::table('kriteria')->where('kriteria_id', $id)->first();
        
        if ($kriteria->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/kriteria')->with('error', 'Anda tidak bisa hapus data ini');
        }

        DB::table('kriteria')->where('kriteria_id', $id)->delete();
        return redirect('/dashboard/kriteria')->with('success', 'Kriteria berhasil dihapus');
    }
}
