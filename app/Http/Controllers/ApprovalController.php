<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id != 4) {
            return redirect('/dashboard')->with('error', 'Hanya direktur yang bisa approve');
        }

        $standar = DB::table('standar_mutu')->where('status', 'Draft')->get();
        $kriteria = DB::table('kriteria')->where('status', 'Draft')->get();
        $indikator = DB::table('indikator_kinerja')->where('status', 'Draft')->get();

        return view('dashboard.approval.index', compact('standar', 'kriteria', 'indikator'));
    }

    public function approved(Request $request)
    {
        if (auth()->user()->role_id != 4) {
            return redirect('/dashboard')->with('error', 'Hanya direktur yang bisa akses');
        }

        $type = $request->type ?? 'standar';

        if ($type == 'standar') {
            $data = DB::table('standar_mutu')
                ->join('users', 'standar_mutu.disetujui_oleh', '=', 'users.user_id')
                ->where('standar_mutu.status', 'Disetujui')
                ->select('standar_mutu.*', 'users.name as penyetuju')
                ->orderBy('standar_mutu.tanggal_disetujui', 'desc')
                ->get();
        } elseif ($type == 'kriteria') {
            $data = DB::table('kriteria')
                ->join('users', 'kriteria.disetujui_oleh', '=', 'users.user_id')
                ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
                ->where('kriteria.status', 'Disetujui')
                ->select('kriteria.*', 'users.name as penyetuju', 'standar_mutu.nama_standar')
                ->orderBy('kriteria.tanggal_disetujui', 'desc')
                ->get();
        } else {
            $data = DB::table('indikator_kinerja')
                ->join('users', 'indikator_kinerja.disetujui_oleh', '=', 'users.user_id')
                ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
                ->where('indikator_kinerja.status', 'Disetujui')
                ->select('indikator_kinerja.*', 'users.name as penyetuju', 'kriteria.nama_kriteria')
                ->orderBy('indikator_kinerja.tanggal_disetujui', 'desc')
                ->get();
        }

        return view('dashboard.approval.approved', compact('data', 'type'));
    }

    public function approve(Request $request)
    {
        if (auth()->user()->role_id != 4) {
            return redirect('/dashboard')->with('error', 'Hanya direktur yang bisa approve');
        }

        $type = $request->type;
        $id = $request->id;

        if ($type == 'standar') {
            DB::table('standar_mutu')->where('standar_id', $id)->update([
                'status' => 'Disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);
        } elseif ($type == 'kriteria') {
            DB::table('kriteria')->where('kriteria_id', $id)->update([
                'status' => 'Disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);
        } elseif ($type == 'indikator') {
            DB::table('indikator_kinerja')->where('indikator_id', $id)->update([
                'status' => 'Disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);
        } elseif ($type == 'penetapan') {
            DB::table('penetapan')->where('penetapan_id', $id)->update([
                'status' => 'Disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);
            
            // Auto-create pelaksanaan when penetapan is approved
            $penetapan = DB::table('penetapan')->where('penetapan_id', $id)->first();
            DB::table('pelaksanaan')->insert([
                'penetapan_id' => $penetapan->penetapan_id,
                'tanggal_mulai' => $penetapan->tanggal_rencana_mulai ?: now()->addDays(7),
                'tanggal_selesai' => $penetapan->tanggal_rencana_selesai ?: now()->addDays(30),
                'pic' => $penetapan->pic,
                'status' => 'Belum Dimulai',
                'dibuat_oleh' => $penetapan->dibuat_oleh,
                'tanggal_dibuat' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil disetujui');
    }

    public function reject(Request $request)
    {
        if (auth()->user()->role_id != 4) {
            return redirect('/dashboard')->with('error', 'Hanya direktur yang bisa reject');
        }

        $request->validate(['alasan' => 'required']);

        $type = $request->type;
        $id = $request->id;

        if ($type == 'standar') {
            DB::table('standar_mutu')->where('standar_id', $id)->update([
                'status' => 'Ditolak',
                'deskripsi' => DB::raw("CONCAT(deskripsi, '\n\nDitolak: ', '" . $request->alasan . "')"),
            ]);
        } elseif ($type == 'kriteria') {
            DB::table('kriteria')->where('kriteria_id', $id)->update([
                'status' => 'Ditolak',
                'deskripsi' => DB::raw("CONCAT(COALESCE(deskripsi, ''), '\n\nDitolak: ', '" . $request->alasan . "')"),
            ]);
        } elseif ($type == 'indikator') {
            $current = DB::table('indikator_kinerja')->where('indikator_id', $id)->first();
            $keterangan = (isset($current->keterangan) && $current->keterangan ? $current->keterangan . "\n\n" : '') . 'Ditolak: ' . $request->alasan;
            DB::table('indikator_kinerja')->where('indikator_id', $id)->update([
                'status' => 'Ditolak',
                'keterangan' => $keterangan,
            ]);
        } elseif ($type == 'penetapan') {
            DB::table('penetapan')->where('penetapan_id', $id)->update([
                'status' => 'Ditolak',
                'target_capaian' => DB::raw("CONCAT(target_capaian, '\n\nDitolak: ', '" . $request->alasan . "')"),
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil ditolak');
    }

    public function penetapan(Request $request)
    {
        if (auth()->user()->role_id != 4) {
            return redirect('/dashboard')->with('error', 'Hanya direktur yang bisa akses');
        }

        $query = DB::table('penetapan')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users', 'penetapan.dibuat_oleh', '=', 'users.user_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->where('penetapan.status', 'Draft')
            ->select('penetapan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'users.name as pembuat', 'unit.nama_unit');

        $penetapan = $query->orderBy('penetapan.tanggal_dibuat', 'desc')->get();

        return view('dashboard.approval.penetapan', compact('penetapan'));
    }
}
