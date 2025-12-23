<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('audit')
            ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as auditor', 'audit.auditor_id', '=', 'auditor.user_id')
            ->join('users as pembuat', 'pelaksanaan.dibuat_oleh', '=', 'pembuat.user_id')
            ->join('unit', 'pembuat.unit_id', '=', 'unit.unit_id')
            ->select('audit.*', 'kriteria.nama_kriteria', 'indikator_kinerja.nama_indikator', 'penetapan.tahun', 'auditor.name as auditor', 'unit.nama_unit');

        if ($request->tahun) {
            $query->where('penetapan.tahun', $request->tahun);
        }
        if ($request->unit_id) {
            $query->where('unit.unit_id', $request->unit_id);
        }

        $laporan = $query->orderBy('audit.tanggal_audit', 'desc')->get();
        $units = DB::table('unit')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('dashboard.laporan.index', compact('laporan', 'units', 'tahun_list'));
    }

    public function pdf(Request $request)
    {
        $query = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'pelaksanaan.dibuat_oleh', '=', 'pembuat.user_id')
            ->join('unit', 'pembuat.unit_id', '=', 'unit.unit_id')
            ->select('pelaksanaan.*', 'kriteria.nama_kriteria', 'indikator_kinerja.nama_indikator', 'indikator_kinerja.kode_indikator', 'indikator_kinerja.target', 'penetapan.tahun', 'penetapan.target_capaian', 'penetapan.anggaran', 'penetapan.pic', 'unit.nama_unit');

        if ($request->tahun) {
            $query->where('penetapan.tahun', $request->tahun);
        }
        if ($request->unit_id) {
            $query->where('unit.unit_id', $request->unit_id);
        }

        $pelaksanaan = $query->get();
        
        // Get audit data for each pelaksanaan
        foreach ($pelaksanaan as $item) {
            $audits = DB::table('audit')
                ->join('users as auditor', 'audit.auditor_id', '=', 'auditor.user_id')
                ->where('audit.pelaksanaan_id', $item->pelaksanaan_id)
                ->select('audit.*', 'auditor.name as auditor')
                ->get();
            
            $item->evaluasi_kesesuaian = $audits->pluck('evaluasi_kesesuaian')->unique()->implode(', ');
            $item->hasil_audit = $audits->pluck('hasil_audit')->unique()->implode(', ');
            $item->rekomendasi_perbaikan = $audits->pluck('rekomendasi_perbaikan')->filter()->unique()->implode(', ');
            $item->catatan_penutupan = $audits->pluck('catatan_penutupan')->filter()->unique()->implode(', ');
            $item->auditor = $audits->pluck('auditor')->unique()->implode(', ');
        }

        $pdf = Pdf::loadView('dashboard.laporan.pdf', ['laporan' => $pelaksanaan]);
        return $pdf->download('laporan-audit-'.date('Y-m-d').'.pdf');
    }
}
