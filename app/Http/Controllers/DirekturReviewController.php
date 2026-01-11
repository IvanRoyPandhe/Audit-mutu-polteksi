<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirekturReviewController extends Controller
{
    public function index(Request $request)
    {
        // Get completed audits that need review
        $query = DB::table('audit')
            ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
            ->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->where('pelaksanaan.status', 'Selesai')
            ->select(
                'audit.*',
                'indikator_kinerja.nama_indikator',
                'kriteria.nama_kriteria',
                'standar_mutu.nama_standar',
                'unit.nama_unit',
                'penetapan.tahun',
                'pelaksanaan.tanggal_mulai',
                'pelaksanaan.tanggal_selesai'
            );

        // Filter
        if ($request->status_review) {
            if ($request->status_review === 'reviewed') {
                $query->whereNotNull('audit.catatan_peningkatan');
            } else {
                $query->whereNull('audit.catatan_peningkatan');
            }
        }

        if ($request->unit_id) {
            $query->where('unit.unit_id', $request->unit_id);
        }

        if ($request->tahun) {
            $query->where('penetapan.tahun', $request->tahun);
        }

        $audits = $query->orderBy('pelaksanaan.tanggal_selesai', 'desc')->paginate(20);
        $units = DB::table('unit')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('dashboard.direktur-review.index', compact('audits', 'units', 'tahun_list'));
    }

    public function show($id)
    {
        $audit = DB::table('audit')
            ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
            ->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->where('audit.audit_id', $id)
            ->select(
                'audit.*',
                'indikator_kinerja.nama_indikator',
                'indikator_kinerja.target',
                'kriteria.nama_kriteria',
                'standar_mutu.nama_standar',
                'unit.nama_unit',
                'penetapan.tahun',
                'penetapan.target_capaian',
                'pelaksanaan.tanggal_mulai',
                'pelaksanaan.tanggal_selesai',
                'pelaksanaan.status'
            )
            ->first();

        if (!$audit) {
            abort(404);
        }

        return view('dashboard.direktur-review.show', compact('audit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'catatan_peningkatan' => 'required|string',
        ]);

        DB::table('audit')->where('audit_id', $id)->update([
            'catatan_peningkatan' => $request->catatan_peningkatan,
            'tanggal_review_direktur' => now(),
            'direview_oleh' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Catatan peningkatan berhasil disimpan');
    }
}
