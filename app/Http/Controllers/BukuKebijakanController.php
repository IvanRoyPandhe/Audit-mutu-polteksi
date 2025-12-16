<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuKebijakanController extends Controller
{
    public function kebijakan()
    {
        $data = DB::table('buku_kebijakan')->where('tipe', 'kebijakan')->orderBy('created_at', 'desc')->get();
        return view('dashboard.buku-kebijakan.kebijakan', compact('data'));
    }

    public function manual()
    {
        $data = DB::table('buku_kebijakan')->where('tipe', 'manual')->orderBy('created_at', 'desc')->get();
        return view('dashboard.buku-kebijakan.manual', compact('data'));
    }

    public function formulir()
    {
        $data = DB::table('buku_kebijakan')->where('tipe', 'formulir')->orderBy('created_at', 'desc')->get();
        return view('dashboard.buku-kebijakan.formulir', compact('data'));
    }

    public function create($tipe)
    {
        return view('dashboard.buku-kebijakan.create', compact('tipe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link' => 'required|url',
            'tipe' => 'required|in:kebijakan,manual,formulir',
        ]);

        DB::table('buku_kebijakan')->insert([
            'judul' => $request->judul,
            'link' => $request->link,
            'tipe' => $request->tipe,
            'dibuat_oleh' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/dashboard/' . $request->tipe)->with('success', ucfirst($request->tipe) . ' berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = DB::table('buku_kebijakan')->where('id', $id)->first();
        return view('dashboard.buku-kebijakan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        $item = DB::table('buku_kebijakan')->where('id', $id)->first();
        
        DB::table('buku_kebijakan')->where('id', $id)->update([
            'judul' => $request->judul,
            'link' => $request->link,
            'updated_at' => now(),
        ]);

        return redirect('/dashboard/' . $item->tipe)->with('success', ucfirst($item->tipe) . ' berhasil diupdate');
    }

    public function destroy($id)
    {
        $item = DB::table('buku_kebijakan')->where('id', $id)->first();
        DB::table('buku_kebijakan')->where('id', $id)->delete();
        return redirect('/dashboard/' . $item->tipe)->with('success', ucfirst($item->tipe) . ' berhasil dihapus');
    }
}