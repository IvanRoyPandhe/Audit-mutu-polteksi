<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelaksanaanController extends Controller
{
    public function index(Request $request)
    {
        // Auto update status berdasarkan tanggal
        DB::table('pelaksanaan')
            ->where('status', 'Belum Dimulai')
            ->where('tanggal_mulai', '<=', now())
            ->update(['status' => 'Sedang Berjalan']);

        DB::table('pelaksanaan')
            ->where('status', 'Sedang Berjalan')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now())
            ->update(['status' => 'Selesai']);

        $query = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'pelaksanaan.dibuat_oleh', '=', 'pembuat.user_id')
            ->join('unit', 'pembuat.unit_id', '=', 'unit.unit_id')
            ->select('pelaksanaan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'penetapan.tahun', 'penetapan.target_capaian', 'penetapan.anggaran', 'pembuat.name as pembuat', 'unit.nama_unit');

        if (auth()->user()->role_id == 3) {
            $query->where('pelaksanaan.dibuat_oleh', auth()->id());
        }
        
        // Auditor hanya bisa lihat unit yang ditugaskan
        if (auth()->user()->role_id == 2) {
            $assignedUnits = DB::table('unit_auditors')
                ->where('auditor_id', auth()->id())
                ->pluck('unit_id');
            if ($assignedUnits->isEmpty()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('unit.unit_id', $assignedUnits)
                      ->where('pelaksanaan.status', 'Selesai');
            }
        }

        // Filter untuk admin dan direktur
        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4) {
            if ($request->unit_id) {
                $query->where('unit.unit_id', $request->unit_id);
            }
        }

        // Filter untuk semua role
        if ($request->status) {
            $query->where('pelaksanaan.status', $request->status);
        }
        if ($request->tahun) {
            $query->where('penetapan.tahun', $request->tahun);
        }

        $pelaksanaan = $query->orderBy('unit.nama_unit', 'asc')->orderBy('pelaksanaan.tanggal_dibuat', 'desc')->get();

        // Get auditors for each unit
        foreach ($pelaksanaan as $item) {
            $unitId = DB::table('users')->where('user_id', $item->dibuat_oleh)->value('unit_id');
            $item->auditors = DB::table('unit_auditors')
                ->join('users', 'unit_auditors.auditor_id', '=', 'users.user_id')
                ->where('unit_auditors.unit_id', $unitId)
                ->select('users.name', 'users.user_id')
                ->get();
        }

        $pelaksanaan = $pelaksanaan->groupBy('nama_unit');
        $units = DB::table('unit')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $auditors = DB::table('users')->where('role_id', 2)->get();

        return view('dashboard.pelaksanaan.index', compact('pelaksanaan', 'units', 'tahun_list', 'auditors'));
    }





    public function edit($id)
    {
        $pelaksanaan = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->where('pelaksanaan.pelaksanaan_id', $id)
            ->select('pelaksanaan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'penetapan.tahun', 'penetapan.anggaran')
            ->first();
        
        if ($pelaksanaan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/pelaksanaan')->with('error', 'Anda tidak bisa edit data ini');
        }

        return view('dashboard.pelaksanaan.edit', compact('pelaksanaan'));
    }

    public function update(Request $request, $id)
    {
        $pelaksanaan = DB::table('pelaksanaan')->where('pelaksanaan_id', $id)->first();
        
        if ($pelaksanaan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/pelaksanaan')->with('error', 'Anda tidak bisa edit data ini');
        }

        $request->validate([
            'tanggal_realisasi' => 'nullable|date',
            'dokumen_judul' => 'nullable|array',
            'dokumen_judul.*' => 'nullable|string',
            'dokumen_url' => 'nullable|array',
            'dokumen_url.*' => 'nullable|url',
            'keterangan' => 'nullable',
        ]);

        $dokumenLink = null;
        if ($request->dokumen_judul && $request->dokumen_url) {
            $dokumen = [];
            foreach ($request->dokumen_judul as $index => $judul) {
                if (!empty($judul) && !empty($request->dokumen_url[$index])) {
                    $dokumen[] = [
                        'judul' => $judul,
                        'url' => $request->dokumen_url[$index]
                    ];
                }
            }
            $dokumenLink = !empty($dokumen) ? json_encode($dokumen) : null;
        }

        DB::table('pelaksanaan')->where('pelaksanaan_id', $id)->update([
            'tanggal_realisasi' => $request->tanggal_realisasi,
            'dokumen_link' => $dokumenLink,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/dashboard/pelaksanaan')->with('success', 'Pelaksanaan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pelaksanaan = DB::table('pelaksanaan')->where('pelaksanaan_id', $id)->first();
        
        if ($pelaksanaan->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/pelaksanaan')->with('error', 'Anda tidak bisa hapus data ini');
        }

        DB::table('pelaksanaan')->where('pelaksanaan_id', $id)->delete();
        return redirect('/dashboard/pelaksanaan')->with('success', 'Pelaksanaan berhasil dihapus');
    }

    public function assignAuditor(Request $request, $id)
    {
        $request->validate([
            'auditor_id' => 'required|exists:users,user_id',
        ]);

        try {
            DB::table('pelaksanaan_auditors')->insert([
                'pelaksanaan_id' => $id,
                'auditor_id' => $request->auditor_id,
                'assigned_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Auditor berhasil ditugaskan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Auditor sudah ditugaskan');
        }
    }

    public function removeAuditor($pelaksanaanId, $auditorId)
    {
        DB::table('pelaksanaan_auditors')
            ->where('pelaksanaan_id', $pelaksanaanId)
            ->where('auditor_id', $auditorId)
            ->delete();
        return redirect()->back()->with('success', 'Auditor berhasil dihapus');
    }
}