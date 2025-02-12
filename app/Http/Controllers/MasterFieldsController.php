<?php

namespace App\Http\Controllers;

use App\Models\AjustField;
use App\Models\MasterFields;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterFields::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-field.edit', $row->id) . '" class="btn btn-primary btn-md btn-edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btnDelete = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-md btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></a>';
                    return $btnEdit . ' ' . $btnDelete;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('master.fields.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $masterField = MasterFields::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($masterField)
            ->withProperties(['JudulField' => $masterField->JudulField])
            ->log('Menambahkan Field Baru dengan Judul: "' . $masterField->JudulField . '"');

        return redirect()->route('master-field.index')
            ->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterFields $masterFields)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $field = MasterFields::find($id);
        return view('master.fields.edit', compact('field'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->all();
        $data['DieditOleh'] = auth()->user()->id;
        $field = MasterFields::find($id);
        $field->update($data);
        activity()
            ->causedBy(auth()->user())
            ->performedOn($field)
            ->withProperties(['JudulField' => $field->JudulField])
            ->log('Mengubah Field dengan Judul: "' . $field->JudulField . '"');
        return redirect()->route('master-field.index')
            ->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $masterFields = MasterFields::find($id);
        $masterFields->delete();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($masterFields)
            ->withProperties(['JudulField' => $masterFields->JudulField])
            ->log('Menghapus Field dengan Judul: "' . $masterFields->JudulField . '"');
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }
}
