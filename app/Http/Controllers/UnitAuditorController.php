<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitAuditorController extends Controller
{
    public function index()
    {
        $assignments = DB::table('unit_auditors')
            ->join('users as auditor', 'unit_auditors.auditor_id', '=', 'auditor.user_id')
            ->join('unit', 'unit_auditors.unit_id', '=', 'unit.unit_id')
            ->join('users as admin', 'unit_auditors.assigned_by', '=', 'admin.user_id')
            ->select('unit_auditors.*', 'auditor.name as auditor_name', 'unit.nama_unit', 'admin.name as assigned_by_name')
            ->get()
            ->groupBy('nama_unit');

        $auditors = DB::table('users')->where('role_id', 2)->get();
        $units = DB::table('unit')->get();

        return view('dashboard.unit-auditors.index', compact('assignments', 'auditors', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'auditor_id' => 'required|exists:users,user_id',
            'unit_id' => 'required|exists:unit,unit_id',
        ]);

        try {
            DB::table('unit_auditors')->insert([
                'auditor_id' => $request->auditor_id,
                'unit_id' => $request->unit_id,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Auditor berhasil ditugaskan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Auditor sudah ditugaskan ke unit ini');
        }
    }

    public function destroy($id)
    {
        DB::table('unit_auditors')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Penugasan auditor berhasil dihapus');
    }
}