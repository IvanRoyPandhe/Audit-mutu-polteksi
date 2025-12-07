<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndikatorKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('indikator_kinerja')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'indikator_kinerja.dibuat_oleh', '=', 'pembuat.user_id')
            ->leftJoin('users as penyetuju', 'indikator_kinerja.disetujui_oleh', '=', 'penyetuju.user_id')
            ->select('indikator_kinerja.*', 'kriteria.nama_kriteria', 'kriteria.kode_kriteria', 'pembuat.name as pembuat', 'penyetuju.name as penyetuju');

        if (auth()->user()->role_id != 1) {
            $query->where('indikator_kinerja.dibuat_oleh', auth()->id());
        }

        // Filter untuk admin
        if (auth()->user()->role_id == 1) {
            if ($request->unit_id) {
                $query->join('users as u', 'indikator_kinerja.dibuat_oleh', '=', 'u.user_id')
                      ->where('u.unit_id', $request->unit_id);
            }
            if ($request->status) {
                $query->where('indikator_kinerja.status', $request->status);
            }
        }

        // Filter untuk semua role
        if ($request->kriteria_id) {
            $query->where('indikator_kinerja.kriteria_id', $request->kriteria_id);
        }

        $indikator = $query->orderBy('indikator_kinerja.tanggal_dibuat', 'desc')->get();

        $kriteria_list = DB::table('kriteria')->get();
        $units = DB::table('unit')->get();

        return view('dashboard.indikator-kinerja.index', compact('indikator', 'kriteria_list', 'units'));
    }

    public function resubmit($id)
    {
        $indikator = DB::table('indikator_kinerja')->where('indikator_id', $id)->first();
        
        if ($indikator->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/indikator-kinerja')->with('error', 'Anda tidak bisa ajukan kembali data ini');
        }

        DB::table('indikator_kinerja')->where('indikator_id', $id)->update(['status' => 'Draft']);
        return redirect('/dashboard/indikator-kinerja')->with('success', 'Berhasil diajukan kembali');
    }

    public function create()
    {
        $kriteria = DB::table('kriteria')->get();
        return view('dashboard.indikator-kinerja.create', compact('kriteria'));
    }

    public function show($id)
    {
        $indikator = DB::table('indikator_kinerja')
            ->join('kriteria', 'indikator_kinerja.kriteria_id', '=', 'kriteria.kriteria_id')
            ->join('users as pembuat', 'indikator_kinerja.dibuat_oleh', '=', 'pembuat.user_id')
            ->leftJoin('users as penyetuju', 'indikator_kinerja.disetujui_oleh', '=', 'penyetuju.user_id')
            ->where('indikator_kinerja.indikator_id', $id)
            ->select('indikator_kinerja.*', 'kriteria.nama_kriteria', 'kriteria.kode_kriteria', 'pembuat.name as pembuat', 'penyetuju.name as penyetuju')
            ->first();

        return view('dashboard.indikator-kinerja.show', compact('indikator'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'kode_indikator' => 'required|unique:indikator_kinerja,kode_indikator|max:20',
            'nama_indikator' => 'required|max:200',
            'target' => 'required|array',
            'target.*' => 'required|string',
        ]);

        DB::table('indikator_kinerja')->insert([
            'kriteria_id' => $request->kriteria_id,
            'kode_indikator' => $request->kode_indikator,
            'nama_indikator' => $request->nama_indikator,
            'target' => json_encode($request->target),
            'dibuat_oleh' => auth()->id(),
            'tanggal_dibuat' => now(),
            'status' => 'Draft',
        ]);

        return redirect('/dashboard/indikator-kinerja')->with('success', 'Indikator kinerja berhasil dibuat');
    }

    public function edit($id)
    {
        $indikator = DB::table('indikator_kinerja')->where('indikator_id', $id)->first();
        
        if ($indikator->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/indikator-kinerja')->with('error', 'Anda tidak bisa edit data ini');
        }

        $kriteria = DB::table('kriteria')->get();
        return view('dashboard.indikator-kinerja.edit', compact('indikator', 'kriteria'));
    }

    public function update(Request $request, $id)
    {
        $indikator = DB::table('indikator_kinerja')->where('indikator_id', $id)->first();
        
        if ($indikator->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/indikator-kinerja')->with('error', 'Anda tidak bisa edit data ini');
        }

        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'kode_indikator' => 'required|max:20|unique:indikator_kinerja,kode_indikator,'.$id.',indikator_id',
            'nama_indikator' => 'required|max:200',
            'target' => 'required|array',
            'target.*' => 'required|string',
        ]);

        DB::table('indikator_kinerja')->where('indikator_id', $id)->update([
            'kriteria_id' => $request->kriteria_id,
            'kode_indikator' => $request->kode_indikator,
            'nama_indikator' => $request->nama_indikator,
            'target' => json_encode($request->target),
        ]);

        return redirect('/dashboard/indikator-kinerja')->with('success', 'Indikator kinerja berhasil diupdate');
    }

    public function destroy($id)
    {
        $indikator = DB::table('indikator_kinerja')->where('indikator_id', $id)->first();
        
        if ($indikator->dibuat_oleh != auth()->id() && auth()->user()->role_id != 1) {
            return redirect('/dashboard/indikator-kinerja')->with('error', 'Anda tidak bisa hapus data ini');
        }

        DB::table('indikator_kinerja')->where('indikator_id', $id)->delete();
        return redirect('/dashboard/indikator-kinerja')->with('success', 'Indikator kinerja berhasil dihapus');
    }
}
