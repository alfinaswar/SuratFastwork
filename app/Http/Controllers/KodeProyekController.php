<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\KodeProyek;
use Illuminate\Http\Request;

class KodeProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kodeProyeks = KodeProyek::all();
        return view('master.kode-proyek.index', compact('kodeProyeks'));
    }

    public function create()
    {
        return view('master.kode-proyek.create');
    }

    public function store(Request $request)
    {
        KodeProyek::create($request->all());

        return redirect()->route('master-proyek.index')->with('success', 'Kode Proyek berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kodeProyek = KodeProyek::find($id);
        return view('master.kode-proyek.edit', compact('kodeProyek'));
    }


    public function update(Request $request, $id)
    {
        $kodeProyek = KodeProyek::find($id);
        $kodeProyek->update($request->all());

        return redirect()->route('master-proyek.index')->with('success', 'Kode Proyek berhasil diperbarui');
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('master-departemen.index')->with('success', 'Departemen berhasil dihapus');
    }
}
