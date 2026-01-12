<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SPIController extends Controller
{
    public function index(Request $request)
    {
        // Get all pelaksanaan with status monitoring
        $query = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('standar_mutu', 'kriteria.standar_id', '=', 'standar_mutu.standar_id')
            ->join('users', 'pelaksanaan.dibuat_oleh', '=', 'users.user_id')
            ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
            ->leftJoin('pelaksanaan_auditors', 'pelaksanaan.pelaksanaan_id', '=', 'pelaksanaan_auditors.pelaksanaan_id')
            ->leftJoin('users as auditor', 'pelaksanaan_auditors.auditor_id', '=', 'auditor.user_id')
            ->select(
                'pelaksanaan.*',
                'standar_mutu.nama_standar',
                'kriteria.nama_kriteria',
                'indikator_kinerja.nama_indikator',
                'unit.nama_unit',
                'users.name as pic_name',
                'users.user_id as pic_id',
                'auditor.name as auditor_name',
                'auditor.user_id as auditor_id',
                'penetapan.tahun',
                DB::raw('CASE 
                    WHEN pelaksanaan.status = \'Selesai\' THEN \'Selesai\'
                    WHEN pelaksanaan.tanggal_selesai < NOW() AND pelaksanaan.status != \'Selesai\' THEN \'Terlambat\'
                    WHEN pelaksanaan.tanggal_mulai <= NOW() AND pelaksanaan.status != \'Selesai\' THEN \'Sedang Berjalan\'
                    ELSE \'Belum Mulai\'
                END as status_monitoring')
            );

        // Filters
        if ($request->status_filter) {
            if ($request->status_filter === 'terlambat') {
                $query->where('pelaksanaan.tanggal_selesai', '<', now())
                      ->where('pelaksanaan.status', '!=', 'Selesai');
            } elseif ($request->status_filter === 'berjalan') {
                $query->where('pelaksanaan.tanggal_mulai', '<=', now())
                      ->where('pelaksanaan.status', '!=', 'Selesai')
                      ->where('pelaksanaan.tanggal_selesai', '>=', now());
            } elseif ($request->status_filter === 'selesai') {
                $query->where('pelaksanaan.status', 'Selesai');
            }
        }

        if ($request->unit_id) {
            $query->where('unit.unit_id', $request->unit_id);
        }

        if ($request->tahun) {
            $query->where('penetapan.tahun', $request->tahun);
        }

        $pelaksanaan = $query->orderBy('pelaksanaan.tanggal_selesai', 'asc')->paginate(20);

        // Get filter options
        $units = DB::table('unit')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Statistics
        $stats = [
            'total' => DB::table('pelaksanaan')->count(),
            'selesai' => DB::table('pelaksanaan')->where('status', 'Selesai')->count(),
            'terlambat' => DB::table('pelaksanaan')
                ->where('tanggal_selesai', '<', now())
                ->where('status', '!=', 'Selesai')
                ->count(),
            'berjalan' => DB::table('pelaksanaan')
                ->where('tanggal_mulai', '<=', now())
                ->where('status', '!=', 'Selesai')
                ->where('tanggal_selesai', '>=', now())
                ->count(),
        ];

        return view('dashboard.spi.index', compact('pelaksanaan', 'units', 'tahun_list', 'stats'));
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:reminder,warning,info',
            'pelaksanaan_id' => 'nullable|integer'
        ]);

        foreach ($request->user_ids as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'pelaksanaan_id' => $request->pelaksanaan_id,
                'sent_by' => auth()->id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke ' . count($request->user_ids) . ' user'
        ]);
    }

    public function getUsers(Request $request)
    {
        $pelaksanaanId = $request->pelaksanaan_id;
        
        if ($pelaksanaanId) {
            // Get PIC and Auditors for specific pelaksanaan
            $pelaksanaan = DB::table('pelaksanaan')
                ->join('users as pic', 'pelaksanaan.dibuat_oleh', '=', 'pic.user_id')
                ->where('pelaksanaan.pelaksanaan_id', $pelaksanaanId)
                ->select('pic.user_id', 'pic.name', DB::raw('"PIC" as role'))
                ->first();

            $auditors = DB::table('pelaksanaan_auditors')
                ->join('users', 'pelaksanaan_auditors.auditor_id', '=', 'users.user_id')
                ->where('pelaksanaan_auditors.pelaksanaan_id', $pelaksanaanId)
                ->select('users.user_id', 'users.name', DB::raw('"Auditor" as role'))
                ->get();

            $users = collect([$pelaksanaan])->merge($auditors);
        } else {
            // Get all users (PIC and Auditors)
            $users = DB::table('users')
                ->join('role', 'users.role_id', '=', 'role.role_id')
                ->whereIn('users.role_id', [2, 3]) // Auditor and Unit Kerja
                ->select('users.user_id', 'users.name', 'role.role_name as role')
                ->get();
        }

        return response()->json($users);
    }
}