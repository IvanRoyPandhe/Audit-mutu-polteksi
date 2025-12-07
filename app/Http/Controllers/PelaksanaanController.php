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
            ->select('pelaksanaan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'penetapan.tahun', 'penetapan.target_capaian', 'pembuat.name as pembuat', 'unit.nama_unit');

        if (auth()->user()->role_id == 3) {
            $query->where('pelaksanaan.dibuat_oleh', auth()->id());
        }
        
        // Auditor bisa lihat semua pelaksanaan yang selesai
        if (auth()->user()->role_id == 2) {
            $query->where('pelaksanaan.status', 'Selesai');
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

        $pelaksanaan = $query->orderBy('pelaksanaan.tanggal_dibuat', 'desc')->get();

        $units = DB::table('unit')->get();
        $tahun_list = DB::table('penetapan')->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('dashboard.pelaksanaan.index', compact('pelaksanaan', 'units', 'tahun_list'));
    }

    public function create()
    {
        $penetapan = DB::table('penetapan')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users', 'penetapan.dibuat_oleh', '=', 'users.user_id')
            ->where('penetapan.status', 'Disetujui')
            ->where('penetapan.dibuat_oleh', auth()->id())
            ->select('penetapan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria')
            ->get();

        return view('dashboard.pelaksanaan.create', compact('penetapan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penetapan_id' => 'required|exists:penetapan,penetapan_id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
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

        DB::table('pelaksanaan')->insert([
            'penetapan_id' => $request->penetapan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'dokumen_link' => $dokumenLink,
            'keterangan' => $request->keterangan,
            'status' => 'Belum Dimulai',
            'dibuat_oleh' => auth()->id(),
            'tanggal_dibuat' => now(),
        ]);

        return redirect('/dashboard/pelaksanaan')->with('success', 'Pelaksanaan berhasil dibuat');
    }

    public function edit($id)
    {
        $pelaksanaan = DB::table('pelaksanaan')
            ->join('penetapan', 'pelaksanaan.penetapan_id', '=', 'penetapan.penetapan_id')
            ->join('indikator_kinerja', 'penetapan.indikator_id', '=', 'indikator_kinerja.indikator_id')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->where('pelaksanaan.pelaksanaan_id', $id)
            ->select('pelaksanaan.*', 'indikator_kinerja.nama_indikator', 'kriteria.nama_kriteria', 'penetapan.tahun')
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'dokumen_judul' => 'nullable|array',
            'dokumen_judul.*' => 'nullable|string',
            'dokumen_url' => 'nullable|array',
            'dokumen_url.*' => 'nullable|url',
            'keterangan' => 'nullable',
            'status' => 'required|in:Belum Dimulai,Sedang Berjalan,Selesai',
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
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'dokumen_link' => $dokumenLink,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
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
}
