<?php

namespace App\Http\Controllers;

use App\Models\MasterJenis;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;

class DrafterController extends Controller
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
                    if ($row->Status == 'Verified') {
                        $status = ' <span class="alert alert-success alert-dismissible badge-icon fade show">
        <i class="fas fa-check-circle"></i> Verified
    </span>';
                        return $status;
                    } else {
                        $btnEdit = '<a href="' . route('drafter.edit', $row->id) . '" class="btn btn-primary btn-md btn-edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $btnDelete = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-md btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></a>';
                        return $btnEdit . ' ' . $btnDelete;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('drafter.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = MasterJenis::where('Aktif', 'Y')->get();
        $penerima = User::orderBy('name', 'ASC')->get();
        return view('drafter.create', compact('kategori', 'penerima'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idJenis' => 'required',
            'TanggalSurat' => 'required',
            'Lampiran' => 'nullable',
            'PenerimaSurat' => 'required',
            'CarbonCopy' => 'nullable',
            'BlindCarbonCopy' => 'nullable',
            'Perihal' => 'required',
            'Isi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();

        $surat = Surat::create(attributes: [
            'idJenis' => $data['idJenis'],
            'NomorSurat' => $this->GenerateKode(),
            'TanggalSurat' => $data['TanggalSurat'],
            'Lampiran' => $data['Lampiran'],
            'PenerimaSurat' => $data['PenerimaSurat'],
            'CarbonCopy' => $data['CarbonCopy'] ?? null,
            'BlindCarbonCopy' => $data['BlindCarbonCopy'] ?? null,
            'Perihal' => $data['Perihal'],
            'Isi' => $data['Isi'],
            'DibuatOleh' => auth()->user()->id,
        ]);

        $cekKategori = MasterJenis::find($request->idJenis);
        $templatePath = storage_path('app/public/FormatSurat/' . $cekKategori->FormatSurat);
        $savePath = storage_path('app/public/surat' . $cekKategori->JenisSurat . '.docx');
        if (!file_exists($templatePath)) {
            return redirect()->route('drafter.index')->withErrors(['message' => 'Template tidak ditemukan']);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $dataWord = [
            'Nama' => $request->Lampiran,
            'Isi' => $request->Isi,
        ];
        foreach ($dataWord as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        $templateProcessor->saveAs($savePath);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['Perihal' => $data['Perihal']])
            ->log('Menambahkan Surat Baru dengan Nomor: "' . $this->GenerateKode() . '"');

        return redirect()->route('drafter.index')->with('success', 'Surat berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $surat = Surat::with('getCatatan')->findOrFail($id);
        $kategori = MasterJenis::all();
        $penerima = User::all();

        return view('drafter.edit', compact('surat', 'kategori', 'penerima'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'idJenis' => 'required',
            'TanggalSurat' => 'required',
            'Lampiran' => 'nullable',
            'PenerimaSurat' => 'required',
            'CarbonCopy' => 'nullable',
            'BlindCarbonCopy' => 'nullable',
            'Perihal' => 'required',
            'Isi' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $surat = Surat::findOrFail($id);

        $data = $request->all();

        $surat->update([
            'idJenis' => json_encode($data['idJenis']),
            'TanggalSurat' => $data['TanggalSurat'],
            'Lampiran' => $data['Lampiran'],
            'PenerimaSurat' => $data['PenerimaSurat'],
            'CarbonCopy' => $data['CarbonCopy'] ?? null,
            'BlindCarbonCopy' => $data['BlindCarbonCopy'] ?? null,
            'Perihal' => $data['Perihal'],
            'Isi' => $data['Isi'],
            'DieditOleh' => auth()->user()->id,
        ]);

        // Log update
        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['Perihal' => $data['Perihal']])
            ->log('Mengubah Surat dengan Perihal: "' . $this->GenerateKode() . '"');

        return redirect()->route('drafter.index')->with('success', 'Surat Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $surat = Surat::find($id);
        if ($surat) {
            $surat->delete();
            activity()
                ->causedBy(auth()->user())
                ->performedOn($surat)
                ->log('Menghapus Surat dengan Perihal: ' . $surat->Perihal);

            return response()->json(['message' => 'Surat berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'Surat tidak ditemukan'], 404);
        }
    }

    private function GenerateKode()
    {
        $companyName = 'PT';
        $jenisSurat = 'SURAT';
        $month = date('m');
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $monthRoman = $romanMonths[$month - 1];
        $year = date('Y');
        $kode = Surat::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        if ($kode) {
            $lastId = (int) substr($kode->id, 0, 4);  // Get the numeric part of the last ID
            $kodeSurat = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT) . '/' . $companyName . '/' . $jenisSurat . '/' . $year;
        } else {
            $kodeSurat = '0001/' . $companyName . '/' . $jenisSurat . '/' . $year;
        }

        return $kodeSurat;
    }
}
