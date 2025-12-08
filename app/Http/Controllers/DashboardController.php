<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $roleId = auth()->user()->role_id;
        $userId = auth()->id();

        if ($roleId == 3) {
            // Unit Kerja - hanya data mereka
            $stats = [
                'total_penetapan' => DB::table('penetapan')->where('dibuat_oleh', $userId)->count(),
                'total_pelaksanaan' => DB::table('pelaksanaan')->where('dibuat_oleh', $userId)->count(),
                'pelaksanaan_selesai' => DB::table('pelaksanaan')->where('dibuat_oleh', $userId)->where('status', 'Selesai')->count(),
                'total_audit' => DB::table('audit')
                    ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
                    ->where('pelaksanaan.dibuat_oleh', $userId)
                    ->count(),
            ];

            // Chart data untuk unit kerja
            $chartData = [
                'evaluasi' => DB::table('audit')
                    ->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
                    ->where('pelaksanaan.dibuat_oleh', $userId)
                    ->select('evaluasi_kesesuaian', DB::raw('count(*) as total'))
                    ->groupBy('evaluasi_kesesuaian')
                    ->get(),
                'pelaksanaan_per_bulan' => DB::table('pelaksanaan')
                    ->where('dibuat_oleh', $userId)
                    ->select(DB::raw('EXTRACT(MONTH FROM tanggal_mulai) as bulan'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_mulai)'))
                    ->orderBy('bulan')
                    ->get(),
            ];

            return view('dashboard.index', compact('stats', 'chartData'));
        } else {
            // Admin, Direktur, Auditor - semua data dengan filter
            $query = DB::table('penetapan')
                ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
                ->join('users', 'penetapan.dibuat_oleh', '=', 'users.user_id')
                ->join('unit', 'users.unit_id', '=', 'unit.unit_id');

            if ($request->unit_id) {
                $query->where('unit.unit_id', $request->unit_id);
            }
            if ($request->tahun) {
                $query->where('penetapan.tahun', $request->tahun);
            }

            $stats = [
                'total_penetapan' => (clone $query)->count(),
                'total_pelaksanaan' => DB::table('pelaksanaan')
                    ->when($request->unit_id, function($q) use ($request) {
                        $q->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
                          ->where('users.unit_id', $request->unit_id);
                    })
                    ->count(),
                'pelaksanaan_selesai' => DB::table('pelaksanaan')
                    ->when($request->unit_id, function($q) use ($request) {
                        $q->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
                          ->where('users.unit_id', $request->unit_id);
                    })
                    ->where('pelaksanaan.status', 'Selesai')
                    ->count(),
                'total_audit' => DB::table('audit')
                    ->when($request->unit_id, function($q) use ($request) {
                        $q->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
                          ->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
                          ->where('users.unit_id', $request->unit_id);
                    })
                    ->count(),
            ];

            // Chart data
            $chartData = [
                'evaluasi' => DB::table('audit')
                    ->when($request->unit_id, function($q) use ($request) {
                        $q->join('pelaksanaan', 'audit.pelaksanaan_id', '=', 'pelaksanaan.pelaksanaan_id')
                          ->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
                          ->where('users.unit_id', $request->unit_id);
                    })
                    ->when($request->tahun, function($q) use ($request) {
                        $q->join('pelaksanaan as p2', 'audit.pelaksanaan_id', '=', 'p2.pelaksanaan_id')
                          ->join('penetapan', 'p2.penetapan_id', '=', 'penetapan.penetapan_id')
                          ->where('penetapan.tahun', $request->tahun);
                    })
                    ->select('evaluasi_kesesuaian', DB::raw('count(*) as total'))
                    ->groupBy('evaluasi_kesesuaian')
                    ->get(),
                'pelaksanaan_per_bulan' => DB::table('pelaksanaan')
                    ->when($request->unit_id, function($q) use ($request) {
                        $q->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
                          ->where('users.unit_id', $request->unit_id);
                    })
                    ->when($request->tahun, function($q) use ($request) {
                        $q->whereRaw('EXTRACT(YEAR FROM pelaksanaan.tanggal_mulai) = ?', [$request->tahun]);
                    })
                    ->select(DB::raw('EXTRACT(MONTH FROM pelaksanaan.tanggal_mulai) as bulan'), DB::raw('count(*) as total'))
                    ->groupBy(DB::raw('EXTRACT(MONTH FROM pelaksanaan.tanggal_mulai)'))
                    ->orderBy('bulan')
                    ->get(),
                'per_unit' => DB::table('penetapan')
                    ->join('users', 'penetapan.dibuat_oleh', '=', 'users.user_id')
                    ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
                    ->when($request->tahun, function($q) use ($request) {
                        $q->where('penetapan.tahun', $request->tahun);
                    })
                    ->select('unit.nama_unit', DB::raw('count(*) as total'))
                    ->groupBy('unit.nama_unit')
                    ->get(),
            ];

            $units = DB::table('unit')->get();
            $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

            return view('dashboard.index', compact('stats', 'chartData', 'units', 'tahun_list'));
        }
    }
}
