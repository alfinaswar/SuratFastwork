<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departemen;

class MasterDepartemenController extends Controller
{
    public function index()
    {
        $departemens = Departemen::all();
        return view('master.departemen.index', compact('departemens'));
    }

    public function create()
    {
        return view('master.departemen.create');
    }

    public function store(Request $request)
    {
        Departemen::create($request->all());

        return redirect()->route('master-departemen.index')->with('success', 'Departemen berhasil ditambahkan');
    }

    public function edit($id)
    {
        $departemen = Departemen::find($id);
        return view('master.departemen.edit', compact('departemen'));
    }


    public function update(Request $request, $id)
    {
        $departemen = Departemen::find($id);
        $departemen->update($request->all());

        return redirect()->route('master-departemen.index')->with('success', 'Departemen berhasil diperbarui');
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('master-departemen.index')->with('success', 'Departemen berhasil dihapus');
    }
}
