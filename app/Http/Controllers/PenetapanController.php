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
        // Debug: Check all criteria first
        $allKriteria = DB::table('kriteria')
            ->join('users', 'kriteria.dibuat_oleh', '=', 'users.user_id')
            ->select('kriteria.*', 'users.unit_id', 'users.name')
            ->get();
        
        // Get criteria from same unit with indikator
        $kriteria = DB::table('kriteria')
            ->join('users', 'kriteria.dibuat_oleh', '=', 'users.user_id')
            ->where('users.unit_id', auth()->user()->unit_id)
            ->select('kriteria.*')
            ->get();

        return view('dashboard.penetapan.create', compact('kriteria', 'allKriteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'tahun' => 'required|integer',
            'target_capaian' => 'required|array',
            'target_capaian.*' => 'required|string',
            'anggaran' => 'nullable|numeric',
            'pic' => 'nullable|string|max:100',
            'tanggal_rencana_mulai' => 'nullable|date',
            'tanggal_rencana_selesai' => 'nullable|date',
        ]);

        // Validate that the selected criteria belongs to the same unit
        $kriteria = DB::table('kriteria')
            ->join('users', 'kriteria.dibuat_oleh', '=', 'users.user_id')
            ->where('kriteria.kriteria_id', $request->kriteria_id)
            ->where('users.unit_id', auth()->user()->unit_id)
            ->first();

        if (!$kriteria) {
            return redirect()->back()->with('error', 'Anda hanya bisa memilih kriteria dari unit Anda sendiri');
        }

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
                'pic' => $request->pic,
                'tanggal_rencana_mulai' => $request->tanggal_rencana_mulai,
                'tanggal_rencana_selesai' => $request->tanggal_rencana_selesai,
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
            'pic' => 'nullable|string|max:100',
            'tanggal_rencana_mulai' => 'nullable|date',
            'tanggal_rencana_selesai' => 'nullable|date',
        ]);

        DB::table('penetapan')->where('penetapan_id', $id)->update([
            'tahun' => $request->tahun,
            'target_capaian' => json_encode($request->target_capaian),
            'anggaran' => $request->anggaran,
            'pic' => $request->pic,
            'tanggal_rencana_mulai' => $request->tanggal_rencana_mulai,
            'tanggal_rencana_selesai' => $request->tanggal_rencana_selesai,
        ]);

        return redirect('/dashboard/penetapan')->with('success', 'Penetapan berhasil diupdate');
    }

    public function destroy($id)
    {
        $penetapan = DB::table('penetapan')->where('penetapan_id', $id)->first();
        
        if (!$penetapan) {
            return redirect('/dashboard/penetapan')->with('error', 'Data tidak ditemukan');
        }
        
        if ($penetapan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/penetapan')->with('error', 'Anda tidak bisa hapus data ini');
        }

        try {
            // Check for related records
            $pelaksanaanCount = DB::table('pelaksanaan')->where('penetapan_id', $id)->count();
            if ($pelaksanaanCount > 0) {
                return redirect('/dashboard/penetapan')->with('error', 'Tidak dapat menghapus penetapan yang sudah memiliki ' . $pelaksanaanCount . ' data pelaksanaan');
            }

            DB::table('penetapan')->where('penetapan_id', $id)->delete();
            return redirect('/dashboard/penetapan')->with('success', 'Penetapan berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting penetapan: ' . $e->getMessage());
            return redirect('/dashboard/penetapan')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }
}
