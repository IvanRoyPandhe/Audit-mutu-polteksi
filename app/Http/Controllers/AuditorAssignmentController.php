<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditorAssignmentController extends Controller
{
    public function index()
    {
        $assignments = DB::table('auditor_unit_assignments')
            ->join('users as auditor', 'auditor_unit_assignments.auditor_id', '=', 'auditor.user_id')
            ->join('unit', 'auditor_unit_assignments.unit_id', '=', 'unit.unit_id')
            ->join('users as admin', 'auditor_unit_assignments.assigned_by', '=', 'admin.user_id')
            ->select('auditor_unit_assignments.*', 'auditor.name as auditor_name', 'unit.nama_unit', 'admin.name as assigned_by_name')
            ->get();

        $auditors = DB::table('users')->where('role_id', 2)->get();
        $units = DB::table('unit')->get();

        return view('dashboard.auditor-assignments.index', compact('assignments', 'auditors', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'auditor_id' => 'required|exists:users,user_id',
            'unit_id' => 'required|exists:unit,unit_id',
        ]);

        try {
            DB::table('auditor_unit_assignments')->insert([
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
        DB::table('auditor_unit_assignments')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Penugasan auditor berhasil dihapus');
    }
}