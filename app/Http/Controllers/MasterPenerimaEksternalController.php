<?php

namespace App\Http\Controllers;

use App\Models\MasterPenerimaEksternal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class MasterPenerimaEksternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $data = MasterPenerimaEksternal::orderBy('id', 'DESC')->get();
        return view('master.penerimaext.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.penerimaext.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        MasterPenerimaEksternal::create($data);
        return redirect()->route('master-penerima-ext.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPenerimaEksternal $masterPenerimaEksternal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = MasterPenerimaEksternal::find($id);
        return view('master.penerimaext.edit', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $masterPenerimaEksternal = MasterPenerimaEksternal::find($id);
        $masterPenerimaEksternal->update($data);
        return redirect()->route('master-penerima-ext.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $masterPenerimaEksternal = MasterPenerimaEksternal::find($id);
        $masterPenerimaEksternal->delete();
        return redirect()->route('master-penerima-ext.index')->with('success', 'Data berhasil dihapus.');
    }

}
