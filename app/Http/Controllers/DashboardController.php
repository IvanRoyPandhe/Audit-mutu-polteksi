<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_penetapan' => DB::table('penetapan')->count(),
            'total_pelaksanaan' => DB::table('pelaksanaan')->count(),
            'total_audit' => DB::table('audit')->count(),
            'pelaksanaan_selesai' => DB::table('pelaksanaan')->where('status', 'Selesai')->count(),
        ];

        return view('dashboard.index', compact('stats'));
    }
}
