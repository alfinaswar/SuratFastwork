<?php

namespace App\Http\Controllers;

use App\Models\MasterJenis;
use App\Models\Surat;
use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpParser\JsonDecoder;
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
                ->rawColumns(['action', 'StatusLabel'])
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

    public function store(Request $request)
    {

        $namaccext = [];
        $data = $request->all();
        $isiSurat = htmlspecialchars_decode($request->Isi);
        $cekKategori = MasterJenis::find($request->idJenis);
        $IdSurat = Surat::latest()->first()->id ?? 0;
        $lampiran = [];

        if ($request->hasFile('Lampiran')) {
            foreach ($request->file('Lampiran') as $file) {
                $path = $file->store('public/lampiran');
                $lampiran[] = basename($path);
            }
        }

        // Simpan data ke database
        $surat = Surat::create([
            'idJenis' => $data['idJenis'],
            'NomorProject' => $this->GenerateKode(),
            'NomorSurat' => $this->GenerateKode(),
            'TanggalSurat' => $data['TanggalSurat'],
            'Lampiran' => json_encode($lampiran),
            'PenerimaSurat' => $data['PenerimaSurat'],
            'PenerimaSuratEks' => $data['PenerimaSuratEksternal'] ?? null,
            'CarbonCopy' => $data['CarbonCopy'] ?? null,
            'CarbonCopyEks' => $data['CarbonCopyExt'] ?? null,
            'BlindCarbonCopy' => $data['BlindCarbonCopyInt'] ?? null,
            'BlindCarbonCopyEks' => $data['BlindCarbonCopyExt'] ?? null,
            'Perihal' => $data['Perihal'],
            'Isi' => $data['Isi'],
            'DibuatOleh' => auth()->user()->id,
            'NamaFile' => $cekKategori->JenisSurat . '-' . $IdSurat,
        ]);




        // Path template & output
        $templatePath = storage_path('app/public/FormatSurat/' . $cekKategori->FormatSurat);
        $docxPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $IdSurat . '.docx');
        $pdfPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $IdSurat . '.pdf');

        if (!file_exists($templatePath)) {
            return redirect()->route('drafter.index')->withErrors(['message' => 'Template tidak ditemukan']);
        }
        // Buat dokumen Word dari template
        $templateProcessor = new TemplateProcessor($templatePath);
        $isiSurat = strip_tags($request->Isi);

        $NamaPenerima = User::with('getDepartmen')->where('id', $request->PenerimaSurat)->first();

        $datasurat = Surat::with('NamaPengirim')->latest()->first();
        $NamaCCInternal = User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopy)->get();

        $NamaCCExternal = User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopyEks)->get();
        $NamaBCCInternal = User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopy)->get();
        $NamaBCCExternal = User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopyEks)->get();
        function formatUserList($users)
        {
            $output = [];
            foreach ($users as $index => $user) {
                $output[] = ($index + 1) . ". " . $user->name . " - " . $user->getDepartmen->NamaDepartemen . " - " . $user->perusahaan;
            }
            return implode("\n", $output);
        }

        $formattedCCInternal = formatUserList($NamaCCInternal);
        $formattedCCExternal = formatUserList($NamaCCExternal);
        $formattedBCCInternal = formatUserList($NamaBCCInternal);
        $formattedBCCExternal = formatUserList($NamaBCCExternal);

        $writer = new PngWriter();
        $link = route('surat-terkirim.download', $surat->id);
        $qrCode = QrCode::create($link)
            ->setSize(100)
            ->setMargin(0);

        $barcode = $writer->write($qrCode)->getDataUri();

        $templateProcessor->setImageValue('Qrcode', $barcode);
        $dataWord = [
            'nomor' => $this->GenerateKode(),
            'kodeproyek' => $this->GenerateKode(),
            'tanggalterbit' => $data['TanggalSurat'],
            'penerima_int' => $NamaPenerima->name,
            'penerima_eks' => $NamaPenerima->name,
            'inisialpenerima' => $NamaPenerima->inisial,
            'jabatanpenerima' => $NamaPenerima->jabatan,
            'departpenerima' => $NamaPenerima->getDepartmen->NamaDepartemen,
            'perusahaanpenerima' => $data['PerusahaanInt'],
            'alamat' => $data['AlamatInt'],
            'Jabatan' => $NamaPenerima->jabatan,
            'email' => $NamaPenerima->email,
            'website' => $NamaPenerima->website,
            'perihal' => $request->Perihal,
            'pengirim' => $datasurat->NamaPengirim->name ?? null,
            'inisialpengirim' => $datasurat->NamaPengirim->inisial ?? null,
            'jabatpengirim' => $datasurat->NamaPengirim->jabatan ?? null,
            'departpengirim' => $datasurat->NamaPengirim->department ?? null,
            'perusahaanpengirim' => $datasurat->NamaPengirim->perusahaan ?? null,
            'ccint' => $formattedCCInternal,
            'ccxt' => $formattedCCExternal,
            'bccint' => $formattedBCCInternal,
            'bccext' => $formattedBCCExternal,
            'kodeinisialbcc' => null,
            'jabatancclist' => null,
            'departemencc' => null,
            'perusahaancc' => null,
            'jabatanbcc' => null,
            'departemenbcc' => null,
            'perusahaanbcc' => null,
            'kodedrafter' => null,
            'kodeverificator' => null,
            'kodeapprover' => null,
            'isi' => $isiSurat,
            'Qrcode' => $request->Qrcode ?? 'Tidak ada',
            'Pengirim' => auth()->user()->name,
            'JabatanPengirim' => auth()->user()->jabatan,
            'Lampiran' => implode(', ', $lampiran ? array_map(fn($lampiran) => basename($lampiran), $lampiran) : ['Tidak ada']),
        ];

        foreach ($dataWord as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        // Simpan file Word (.docx)
        $templateProcessor->saveAs($docxPath);

        // Konversi DOCX ke PDF menggunakan MPDF dengan perbaikan untuk mengatasi masalah halaman kosong dan jumlah halaman yang tidak sesuai
        // $phpWord = IOFactory::load($docxPath);
        // $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        // ob_start();
        // $htmlWriter->save('php://output');
        // $htmlContent = ob_get_clean();

        // $mpdf = new Mpdf();
        // $mpdf->WriteHTML($htmlContent);
        // $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);  // Simpan sebagai file PDF dengan perbaikan untuk mengatasi masalah halaman kosong dan jumlah halaman yang tidak sesuai

        // Catat aktivitas
        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['Perihal' => $data['Perihal']])
            ->log('Menambahkan Surat Baru dengan Nomor: "' . $this->GenerateKode() . '"');

        return redirect()->route('drafter.index')->with('success', 'Surat berhasil disimpan dalam format DOCX dan PDF.');
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
        $surat = Surat::with('getCatatan', 'getVerifikator')->findOrFail($id);
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
        $lampiran = [];

        if ($request->hasFile('Lampiran')) {
            foreach ($request->file('Lampiran') as $file) {
                $path = $file->store('public/lampiran');
                $lampiran[] = basename($path);
            }
        }
        $data = $request->all();

        $surat->update([
            'idJenis' => json_encode($data['idJenis']),
            'TanggalSurat' => $data['TanggalSurat'],
            'Lampiran' => json_encode($lampiran),
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
