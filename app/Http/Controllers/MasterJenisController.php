<?php

namespace App\Http\Controllers;

use App\Models\MasterJenis;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterJenisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterJenis::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusLabel = $row->Aktif == 'Y'
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-danger">Tidak Aktif</span>';
                    return $statusLabel;
                })
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('kategori-surat.edit', $row->id) . '" class="btn btn-primary btn-md btn-edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btnDelete = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-md btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></a>';
                    return $btnEdit . ' ' . $btnDelete;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('kategori-surat.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-surat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idJenis' => 'required|string|max:50',
            'JenisSurat' => 'required|string|max:255',
            'Aktif' => 'required|in:Y,N',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        $data['DibuatOleh'] = auth()->user()->id;
        $masterJenis = MasterJenis::create($data);
        activity()
            ->causedBy(auth()->user())
            ->performedOn($masterJenis)
            ->withProperties(['JenisSurat' => $request->JenisSurat])
            ->log('Menambahkan Jenis Surat Baru: "' . $request->JenisSurat . '"');
        return redirect()->route('kategori-surat.index')->with('success', 'Jenis Surat Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterJenis $masterJenis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $masterJenis = MasterJenis::find($id);
        return view('kategori-surat.edit', compact('masterJenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'idJenis' => 'required|string|max:50',
            'JenisSurat' => 'required|string|max:255',
            'Aktif' => 'required|in:Y,N',
        ]);

        if ($validatedData->fails()) {
            return redirect()
                ->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $masterJenis = MasterJenis::find($id);

        if (!$masterJenis) {
            return redirect()->route('kategori-surat.index')->withErrors(['Jenis Surat tidak ditemukan.']);
        }
        $data = $request->all();
        $data['DiperbaruiOleh'] = auth()->user()->id;
        $masterJenis->update($data);
        activity()
            ->causedBy(auth()->user())
            ->performedOn($masterJenis)
            ->log('Mengupdate Jenis Surat Dari : ' . $masterJenis->JenisSurat . ' Menjadi ' . $request->JenisSurat);

        return redirect()->route('kategori-surat.index')->with('success', 'Jenis Surat Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cek = Surat::where('idJenis', $id);
        if ($cek) {
            return response()->json(['message' => 'Jenis Surat Sedang Digunakan'], 404);
        }
        $masterJenis = MasterJenis::find($id);
        if ($masterJenis) {
            $masterJenis->delete();
            activity()
                ->causedBy(auth()->user())
                ->performedOn($masterJenis)
                ->log('Menghapus Jenis Surat: ' . $masterJenis->JenisSurat);

            return response()->json(['message' => 'Jenis Surat berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'Jenis Surat tidak ditemukan'], 404);
        }
    }
}