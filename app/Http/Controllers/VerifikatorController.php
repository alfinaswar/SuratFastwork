<?php

namespace App\Http\Controllers;

use App\Models\CatatanSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VerifikatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('verifikator.show', $row->id) . '" class="btn btn-info btn-md btn-show" title="Show"><i class="fas fa-eye"></i></a>';
                    return $btnShow;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('verifikator.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'Status' => 'required|string',
            'Catatan' => 'nullable|string',
            'idsurat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();

        $surat = Surat::find($request->idsurat);
        // dd($surat);
        $surat->update([
            'Status' => $data['Status'],
            'ApprovedBy' => auth()->user()->id,
            'DieditOleh' => auth()->user()->id,
        ]);
        $catatanSurat = CatatanSurat::updateOrCreate(
            ['idSurat' => $data['idsurat']],
            [
                'Status' => $data['Status'],
                'Catatan' => $data['Catatan'] ?? null,
                'DibuatOleh' => auth()->user()->id,
                'DieditOleh' => auth()->user()->id,
            ]
        );

        activity()
            ->causedBy(auth()->user())
            ->performedOn($catatanSurat)
            ->withProperties(['status' => $data['Status'], 'revisi' => $data['Catatan']])
            ->log('Status dengan Perihal: ' . $surat->NomorSurat . ' surat telah diperbarui');

        return redirect()->route('verifikator.index')->with('success', 'Status Surat Berhasil Diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surat = Surat::with('getPenerima', 'getPenulis')->findOrFail($id);
        // dd($surat);
        return view('verifikator.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
