<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenetapanController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('penetapan')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'penetapan.dibuat_oleh', '=', 'pembuat.user_id')
            ->join('unit', 'pembuat.unit_id', '=', 'unit.unit_id')
            ->select('penetapan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'pembuat.name as pembuat', 'unit.nama_unit');

        // Filter untuk unit kerja - hanya lihat data mereka
        if (auth()->user()->role_id == 3) {
            $query->where('penetapan.dibuat_oleh', auth()->id());
        }
        
        // Auditor bisa lihat semua penetapan yang disetujui
        if (auth()->user()->role_id == 2) {
            $query->where('penetapan.status', 'Disetujui');
        }

        // Filter untuk admin
        if (auth()->user()->role_id == 1) {
            if ($request->unit_id) {
                $query->where('unit.unit_id', $request->unit_id);
            }
            if ($request->tahun) {
                $query->where('penetapan.tahun', $request->tahun);
            }
            if ($request->kriteria_id) {
                $query->where('kriteria.kriteria_id', $request->kriteria_id);
            }
        }

        // Filter untuk unit kerja
        if (auth()->user()->role_id == 3) {
            if ($request->tahun) {
                $query->where('penetapan.tahun', $request->tahun);
            }
            if ($request->kriteria_id) {
                $query->where('kriteria.kriteria_id', $request->kriteria_id);
            }
        }

        $penetapan = $query->orderBy('penetapan.tanggal_dibuat', 'desc')->get();

        // Data untuk filter
        $units = DB::table('unit')->get();
        $kriteria = DB::table('kriteria')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('dashboard.penetapan.index', compact('penetapan', 'units', 'kriteria', 'tahun_list'));
    }

    public function create()
    {
        $kriteria = DB::table('kriteria')
            ->join('indikator_kinerja', 'kriteria.kriteria_id', '=', 'indikator_kinerja.kriteria_id')
            ->where('kriteria.status', 'Disetujui')
            ->select('kriteria.*')
            ->distinct()
            ->get();

        return view('dashboard.penetapan.create', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'tahun' => 'required|integer',
            'target_capaian' => 'required|array',
            'target_capaian.*' => 'required|string',
            'anggaran' => 'nullable|numeric',
        ]);

        $targetCapaian = json_encode($request->target_capaian);

        // Get all indikator from selected kriteria
        $indikators = DB::table('indikator_kinerja')
            ->where('kriteria_id', $request->kriteria_id)
            ->get();

        foreach ($indikators as $indikator) {
            DB::table('penetapan')->insert([
                'indikator_id' => $indikator->indikator_id,
                'tahun' => $request->tahun,
                'target_capaian' => $targetCapaian,
                'anggaran' => $request->anggaran,
                'dibuat_oleh' => auth()->id(),
                'tanggal_dibuat' => now(),
                'status' => 'Draft',
            ]);
        }

        return redirect('/dashboard/penetapan')->with('success', 'Penetapan berhasil dibuat');
    }

    public function resubmit($id)
    {
        $penetapan = DB::table('penetapan')->where('penetapan_id', $id)->first();
        
        if ($penetapan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/penetapan')->with('error', 'Anda tidak bisa ajukan kembali data ini');
        }

        DB::table('penetapan')->where('penetapan_id', $id)->update(['status' => 'Draft']);
        return redirect('/dashboard/penetapan')->with('success', 'Berhasil diajukan kembali');
    }

    public function edit($id)
    {
        $penetapan = DB::table('penetapan')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->where('penetapan.penetapan_id', $id)
            ->select('penetapan.*', 'kriteria.nama_kriteria', 'indikator_kinerja.nama_indikator')
            ->first();
        
        if ($penetapan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/penetapan')->with('error', 'Anda tidak bisa edit data ini');
        }

        return view('dashboard.penetapan.edit', compact('penetapan'));
    }

    public function update(Request $request, $id)
    {
        $penetapan = DB::table('penetapan')->where('penetapan_id', $id)->first();
        
        if ($penetapan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/penetapan')->with('error', 'Anda tidak bisa edit data ini');
        }

        $request->validate([
            'tahun' => 'required|integer',
            'target_capaian' => 'required|array',
            'target_capaian.*' => 'required|string',
            'anggaran' => 'nullable|numeric',
        ]);

        DB::table('penetapan')->where('penetapan_id', $id)->update([
            'tahun' => $request->tahun,
            'target_capaian' => json_encode($request->target_capaian),
            'anggaran' => $request->anggaran,
        ]);

        return redirect('/dashboard/penetapan')->with('success', 'Penetapan berhasil diupdate');
    }

    public function destroy($id)
    {
        $penetapan = DB::table('penetapan')->where('penetapan_id', $id)->first();
        
        if ($penetapan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/penetapan')->with('error', 'Anda tidak bisa hapus data ini');
        }

        DB::table('penetapan')->where('penetapan_id', $id)->delete();
        return redirect('/dashboard/penetapan')->with('success', 'Penetapan berhasil dihapus');
    }
}
