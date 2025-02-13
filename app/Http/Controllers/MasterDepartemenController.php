<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDepartemen;

class MasterDepartemenController extends Controller
{
    public function index()
    {
        $departemens = MasterDepartemen::all();
        return view('master.departemen.index', compact('departemens'));
    }

    public function create()
    {
        return view('master.departemen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Kode' => 'required|unique:departemens,Kode',
            'NamaDepartemen' => 'required',
        ]);

        MasterDepartemen::create($request->all());

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan');
    }

    public function edit(MasterDepartemen $departemen)
    {
        return view('master.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, MasterDepartemen $departemen)
    {
        $request->validate([
            'Kode' => 'required|unique:departemens,Kode,' . $departemen->id,
            'NamaDepartemen' => 'required',
        ]);

        $departemen->update($request->all());

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diperbarui');
    }

    public function destroy(MasterDepartemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dihapus');
    }
}
