<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiController extends Controller
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

        if (auth()->user()->role_id == 2) {
            $query->where('audit.auditor_id', auth()->id());
        }

        if ($request->status) {
            $query->where('audit.status', $request->status);
        }
        if ($request->evaluasi) {
            $query->where('audit.evaluasi_kesesuaian', $request->evaluasi);
        }

        $evaluasi = $query->orderBy('audit.tanggal_audit', 'desc')->get();

        return view('dashboard.evaluasi.index', compact('evaluasi'));
    }

    public function create(Request $request)
    {
        $query = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'pelaksanaan.dibuat_oleh', '=', 'pembuat.user_id')
            ->where('pelaksanaan.status', 'Selesai')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('unit_auditors')
                      ->whereColumn('unit_auditors.unit_id', 'pembuat.unit_id');
            })
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('unit_auditors')
                      ->whereColumn('unit_auditors.unit_id', 'pembuat.unit_id')
                      ->whereNotExists(function($subquery) {
                          $subquery->select(DB::raw(1))
                                   ->from('audit')
                                   ->whereColumn('audit.pelaksanaan_id', 'pelaksanaan.pelaksanaan_id')
                                   ->whereColumn('audit.auditor_id', 'unit_auditors.auditor_id');
                      });
            })
            ->select('pelaksanaan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'penetapan.tahun', 'pembuat.unit_id');

        if ($request->pelaksanaan_id) {
            $query->where('pelaksanaan.pelaksanaan_id', $request->pelaksanaan_id);
        }

        $pelaksanaan = $query->get();

        // Get auditors for each unit (only those who haven't audited yet)
        foreach ($pelaksanaan as $item) {
            $item->auditors = DB::table('unit_auditors')
                ->join('users', 'unit_auditors.auditor_id', '=', 'users.user_id')
                ->where('unit_auditors.unit_id', $item->unit_id)
                ->whereNotExists(function($query) use ($item) {
                    $query->select(DB::raw(1))
                          ->from('audit')
                          ->where('audit.pelaksanaan_id', $item->pelaksanaan_id)
                          ->whereColumn('audit.auditor_id', 'unit_auditors.auditor_id');
                })
                ->select('users.name', 'users.user_id')
                ->get();
        }

        return view('dashboard.evaluasi.create', compact('pelaksanaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelaksanaan_id' => 'required|exists:pelaksanaan,pelaksanaan_id',
            'auditor_id' => 'required|exists:users,user_id',
            'tanggal_audit' => 'required|date',
            'evaluasi_kesesuaian' => 'required|in:Sesuai,Tidak Sesuai,Menyimpang,Melampaui',
            'hasil_audit' => 'required|in:Tidak Ada,Minor,Mayor,Observasi',
            'rekomendasi_perbaikan' => 'nullable',
            'catatan_penutupan' => 'nullable',
        ]);

        $request->validate([
            'auditor_id' => 'required|exists:users,user_id',
        ]);
        
        DB::table('audit')->insert([
            'pelaksanaan_id' => $request->pelaksanaan_id,
            'auditor_id' => $request->auditor_id,
            'tanggal_audit' => $request->tanggal_audit,
            'evaluasi_kesesuaian' => $request->evaluasi_kesesuaian,
            'hasil_audit' => $request->hasil_audit,
            'rekomendasi_perbaikan' => $request->rekomendasi_perbaikan,
            'catatan_penutupan' => $request->catatan_penutupan,
            'dibuat_oleh' => auth()->id(),
            'tanggal_dibuat' => now(),
            'status' => 'Draft',
        ]);

        return redirect('/dashboard/evaluasi')->with('success', 'Evaluasi berhasil dibuat');
    }

    public function edit($id)
    {
        $audit = DB::table('audit')
            ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->where('audit.audit_id', $id)
            ->select('audit.*', 'kriteria.nama_kriteria', 'indikator_kinerja.nama_indikator', 'penetapan.tahun')
            ->first();

        $auditor = DB::table('users')->where('role_id', 2)->get();

        return view('dashboard.evaluasi.edit', compact('audit', 'auditor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'auditor_id' => 'required|exists:users,user_id',
            'tanggal_audit' => 'required|date',
            'evaluasi_kesesuaian' => 'required|in:Sesuai,Tidak Sesuai,Menyimpang,Melampaui',
            'hasil_audit' => 'required|in:Tidak Ada,Minor,Mayor,Observasi',
            'rekomendasi_perbaikan' => 'nullable',
            'catatan_penutupan' => 'nullable',
        ]);

        DB::table('audit')->where('audit_id', $id)->update([
            'auditor_id' => $request->auditor_id,
            'tanggal_audit' => $request->tanggal_audit,
            'evaluasi_kesesuaian' => $request->evaluasi_kesesuaian,
            'hasil_audit' => $request->hasil_audit,
            'rekomendasi_perbaikan' => $request->rekomendasi_perbaikan,
            'catatan_penutupan' => $request->catatan_penutupan,
        ]);

        return redirect('/dashboard/evaluasi')->with('success', 'Evaluasi berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('audit')->where('audit_id', $id)->delete();
        return redirect('/dashboard/evaluasi')->with('success', 'Evaluasi berhasil dihapus');
    }
}
