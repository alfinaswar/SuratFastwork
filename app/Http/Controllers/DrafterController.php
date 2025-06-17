<?php

namespace App\Http\Controllers;

use App\Models\KodeProyek;
use App\Models\MasterJenis;
use App\Models\MasterPenerimaEksternal;
use App\Models\Surat;
use App\Models\User;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
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
                        $btnEdit = '<a href="' . route('drafter.edit', $row->id) . '" class="btn btn-info btn-md btn-edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $btnDelete = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-warning btn-md btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></a>';
                        $download = '<a href="' . route('verifikator.download-preview', $row->id) . '" class="btn btn-secondary btn-md btn-download" title="Download"><i class="fas fa-download"></i></a>';
                        $view = '<a href="' . route('drafter.show', $row->id) . '" class="btn btn-light btn-md btn-download" title="Download"><i class="fas fa-eye"></i></a>';
                        return $btnEdit . ' ' . $btnDelete . ' ' . $download . ' ' . $view;
                    }

                })
                ->addColumn('ajukan', function ($row) {
                    if ($row->Status == 'Draft') {
                        $btnAjukan = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-md btn-ajukan" title="Ajukan"><i class="fas fa-paper-plane"></i></a>';
                        return $btnAjukan;
                    } else {
                        return 'Sudah diajukan';
                    }
                })
                ->rawColumns(['action', 'StatusLabel', 'ajukan'])
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
        $eksternal = MasterPenerimaEksternal::get();
        $KodeProject = KodeProyek::get();
        return view('drafter.create', compact('kategori', 'penerima', 'KodeProject', 'eksternal'));
    }
    public function ajukandokumen($id)
    {
        $surat = Surat::find($id);
        // dd($surat);
        $surat->update([
            'Status' => 'Submited',
        ]);

        return redirect()->route('drafter.index')->with('success', 'Surat berhasil diajukan');
    }
    public function getPenerimaSurat()
    {
        $penerima = User::orderBy('name', 'ASC')->get();

        return response()->json(['data' => $penerima]);
    }
    public function store(Request $request)
    {
        $KodeProject = $request->KodeProject;
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
            'NomorProject' => $this->GenerateKode($KodeProject),
            'NomorSurat' => $this->GenerateKode($KodeProject),
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

        // Ambil isi surat dari request (konten HTML)
        $isiSurat = $request->Isi;

        $NamaPenerima = User::with('getDepartmen')->where('id', $request->PenerimaSurat)->first();

        $datasurat = Surat::with('NamaPengirim')->latest()->first();
        $NamaCCInternal = $datasurat->CarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopy)->get() : null;
        $NamaCCExternal = $datasurat->CarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopyEks)->get() : null;
        $NamaBCCInternal = $datasurat->BlindCarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopy)->get() : null;
        $NamaBCCExternal = $datasurat->BlindCarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopyEks)->get() : null;

        function formatUserList($users)
        {
            $output = [];
            foreach ($users as $user) {
                $output[] = $user->name . ' - ' . $user->getDepartmen->NamaDepartemen . ' - ' . $user->perusahaan;
            }
            return implode("\n", $output);
        }

        $formattedCCInternal = $NamaCCInternal ? formatUserList($NamaCCInternal) : null;
        $formattedCCExternal = $NamaCCExternal ? formatUserList($NamaCCExternal) : null;
        $formattedBCCInternal = $NamaBCCInternal ? formatUserList($NamaBCCInternal) : null;
        $formattedBCCExternal = $NamaBCCExternal ? formatUserList($NamaBCCExternal) : null;

        $writer = new PngWriter();
        $link = route('surat.digital', $surat->id);
        $qrCode = QrCode::create($link)
            ->setSize(100)
            ->setMargin(0);

        $barcode = $writer->write($qrCode)->getDataUri();

        // Khusus untuk isi surat, kita konversi HTML ke format Word
        $this->insertHtmlToTemplate($templateProcessor, 'isi', $isiSurat);

        $templateProcessor->setImageValue('Qrcode', $barcode);
        $dataWord = [
            'nomor' => $this->GenerateKode($KodeProject),
            'kodeproyek' => $this->GenerateKode($KodeProject),
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
            // Hapus 'isi' dari sini karena sudah diproses secara terpisah
            'Qrcode' => $request->Qrcode ?? 'Tidak ada',
            'Pengirim' => auth()->user()->name,
            'JabatanPengirim' => auth()->user()->jabatan,
            'Lampiran' => implode(', ', $lampiran ? array_map(fn($lampiran) => basename($lampiran), $lampiran) : ['Tidak ada']),
        ];

        foreach ($dataWord as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        // Simpan dokumen
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
            ->log('Menambahkan Surat Baru dengan Nomor: "' . $this->GenerateKode($KodeProject) . '"');

        return redirect()->route('drafter.index')->with('success', 'Surat berhasil disimpan dalam format DOCX dan PDF.');
    }


    // public function store(Request $request)
    // {
    //     $KodeProject = $request->KodeProject;
    //     $namaccext = [];
    //     $data = $request->all();
    //     $isiSurat = $request->Isi;
    //     $cekKategori = MasterJenis::find($request->idJenis);
    //     $IdSurat = Surat::latest()->first()->id ?? 0;
    //     $lampiran = [];

    //     if ($request->hasFile('Lampiran')) {
    //         foreach ($request->file('Lampiran') as $file) {
    //             $path = $file->store('public/lampiran');
    //             $lampiran[] = basename($path);
    //         }
    //     }

    //     $surat = Surat::create([
    //         'idJenis' => $data['idJenis'],
    //         'NomorProject' => $this->GenerateKode($KodeProject),
    //         'NomorSurat' => $this->GenerateKode($KodeProject),
    //         'TanggalSurat' => $data['TanggalSurat'],
    //         'Lampiran' => json_encode($lampiran),
    //         'PenerimaSurat' => $data['PenerimaSurat'],
    //         'PenerimaSuratEks' => $data['PenerimaSuratEksternal'] ?? null,
    //         'CarbonCopy' => $data['CarbonCopy'] ?? null,
    //         'CarbonCopyEks' => $data['CarbonCopyExt'] ?? null,
    //         'BlindCarbonCopy' => $data['BlindCarbonCopyInt'] ?? null,
    //         'BlindCarbonCopyEks' => $data['BlindCarbonCopyExt'] ?? null,
    //         'Perihal' => $data['Perihal'],
    //         'Isi' => $data['Isi'], // Simpan HTML asli
    //         'DibuatOleh' => auth()->user()->id,
    //         'NamaFile' => $cekKategori->JenisSurat . '-' . $IdSurat,
    //     ]);


    //     $templatePath = storage_path('app/public/FormatSurat/' . $cekKategori->FormatSurat);
    //     $docxPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $IdSurat . '.docx');
    //     $pdfPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $IdSurat . '.pdf');

    //     if (!file_exists($templatePath)) {
    //         return redirect()->route('drafter.index')->with('error', 'Tidak Ditemukan');
    //     }

    //     $templateProcessor = new TemplateProcessor($templatePath);

    //     try {
    //         $templateProcessor->setValue('isi', $isiSurat, ['parseHtml' => true]);
    //     } catch (\Exception $e) {
    //         // Fallback jika konversi HTML gagal
    //         $isiSuratClean = preg_replace('/<[^>]*>/', '', $isiSurat); // Membersihkan tag HTML
    //         $templateProcessor->setValue('isi', $isiSuratClean);
    //         \Log::error('Konversi HTML ke Word gagal: ' . $e->getMessage());
    //     }

    //     // [Sisanya dari kode asli tetap sama]
    //     $NamaPenerima = User::with('getDepartmen')->where('id', $request->PenerimaSurat)->first();

    //     $datasurat = Surat::with('NamaPengirim')->latest()->first();
    //     $NamaCCInternal = $datasurat->CarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopy)->get() : null;
    //     $NamaCCExternal = $datasurat->CarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopyEks)->get() : null;
    //     $NamaBCCInternal = $datasurat->BlindCarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopy)->get() : null;
    //     $NamaBCCExternal = $datasurat->BlindCarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopyEks)->get() : null;

    //     function formatUserList($users)
    //     {
    //         $output = [];
    //         foreach ($users as $user) {
    //             $output[] = $user->name . ' - ' . $user->getDepartmen->NamaDepartemen . ' - ' . $user->perusahaan;
    //         }
    //         return implode("\n", $output);
    //     }

    //     $formattedCCInternal = $NamaCCInternal ? formatUserList($NamaCCInternal) : null;
    //     $formattedCCExternal = $NamaCCExternal ? formatUserList($NamaCCExternal) : null;
    //     $formattedBCCInternal = $NamaBCCInternal ? formatUserList($NamaBCCInternal) : null;
    //     $formattedBCCExternal = $NamaBCCExternal ? formatUserList($NamaBCCExternal) : null;

    //     $writer = new PngWriter();
    //     $link = route('surat.digital', $surat->id);
    //     $qrCode = QrCode::create($link)
    //         ->setSize(100)
    //         ->setMargin(0);

    //     $barcode = $writer->write($qrCode)->getDataUri();

    //     $templateProcessor->setImageValue('Qrcode', $barcode);
    //     $dataWord = [
    //         // [Sisanya dari kode asli tetap   sama]
    //         'isi' => $isiSurat, // Gunakan HTML asli
    //         'Qrcode' => $request->Qrcode ?? 'Tidak ada',
    //         'Pengirim' => auth()->user()->name,
    //         'JabatanPengirim' => auth()->user()->jabatan,
    //         'Lampiran' => implode(', ', $lampiran ? array_map(fn($lampiran) => basename($lampiran), $lampiran) : ['Tidak ada']),
    //     ];

    //     foreach ($dataWord as $key => $value) {
    //         $templateProcessor->setValue($key, $value);
    //     }

    //     // Simpan file Word (.docx)
    //     $templateProcessor->saveAs($docxPath);

    //     // Catat aktivitas
    //     activity()
    //         ->causedBy(auth()->user())
    //         ->performedOn($surat)
    //         ->withProperties(['Perihal' => $data['Perihal']])
    //         ->log('Menambahkan Surat Baru dengan Nomor: "' . $this->GenerateKode($KodeProject) . '"');

    //     return redirect()->route('drafter.index')->with('success', 'Surat berhasil disimpan dalam format DOCX.');
    // }
    public function show($id)
    {
        $surat = Surat::with([
            'getPenerima',
            'getPenerimaEks',
            'getPenulis',
            'NamaPengirim',
            'getCatatan' => function ($query) use ($id) {
                $query->where('DibuatOleh', auth()->user()->id)->where('idSurat', $id);
            }
        ])->findOrFail($id);
        $ambilCC = User::whereIn('id', $surat->CarbonCopy)->get();
        if ($surat->BlindCarbonCopy != null) {
            $ambilBlindCC = User::whereIn('id', $surat->BlindCarbonCopy)->get();
        } else {
            $ambilBlindCC = null;
        }
        $surat['CC'] = $ambilCC;
        $surat['BlindCC'] = $ambilBlindCC;
        return view('drafter.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $surat = Surat::with('getCatatan', 'getVerifikator')->findOrFail($id);
        $kategori = MasterJenis::where('Aktif', 'Y')->get();
        $penerima = User::orderBy('name', 'ASC')->get();
        $eksternal = MasterPenerimaEksternal::get();
        $KodeProject = KodeProyek::get();

        return view('drafter.edit', compact('surat', 'kategori', 'penerima', 'eksternal', 'KodeProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // $data = $request->all();
    //     // dd($data);
    //     $KodeProject = $request->KodeProject;

    //     $surat = Surat::findOrFail($id);
    //     $lampiran = [];

    //     if ($request->hasFile('Lampiran')) {
    //         foreach ($request->file('Lampiran') as $file) {
    //             $path = $file->store('public/lampiran');
    //             $lampiran[] = basename($path);
    //         }
    //     }
    //     $data = $request->all();
    //     // dd($data);

    //     $surat->update([
    //         'idJenis' => $data['idJenis'],
    //         'NomorProject' => $this->GenerateKode($KodeProject),
    //         'NomorSurat' => $this->GenerateKode($KodeProject),
    //         'KodeProject' => $data['KodeProject'],
    //         'TanggalSurat' => $data['TanggalSurat'],
    //         'Lampiran' => json_encode($lampiran),
    //         'PenerimaSurat' => $data['PenerimaSurat'],
    //         'PenerimaSuratEks' => $data['PenerimaSuratEksternal'] ?? null,
    //         'CarbonCopy' => $data['CarbonCopy'] ?? null,
    //         'CarbonCopyEks' => $data['CarbonCopyExt'] ?? null,
    //         'BlindCarbonCopy' => $data['BlindCarbonCopyInt'] ?? null,
    //         'BlindCarbonCopyEks' => $data['BlindCarbonCopyExt'] ?? null,
    //         'Perihal' => $data['Perihal'],
    //         'Isi' => $data['Isi'],
    //         'DibuatOleh' => auth()->user()->id,
    //     ]);

    //     // Log update
    //     activity()
    //         ->causedBy(auth()->user())
    //         ->performedOn($surat)
    //         ->withProperties(['Perihal' => $data['Perihal']])
    //         ->log('Mengubah Surat dengan Perihal: "' . $this->GenerateKode($data['KodeProject']) . '"');

    //     return redirect()->route('drafter.index')->with('success', 'Surat Berhasil Diubah');
    // }
    public function update(Request $request, $id)
    {
        // Cari surat yang akan diupdate
        $surat = Surat::findOrFail($id);

        $KodeProject = $request->KodeProject;
        $namaccext = [];
        $data = $request->all();
        $isiSurat = htmlspecialchars_decode($request->Isi);
        $cekKategori = MasterJenis::find($request->idJenis);
        $lampiran = json_decode($surat->Lampiran, true) ?? []; // Ambil lampiran yang sudah ada

        // Handle upload lampiran baru
        if ($request->hasFile('Lampiran')) {
            // Hapus lampiran lama jika ada
            foreach ($lampiran as $oldFile) {
                $oldPath = storage_path('app/public/lampiran/' . $oldFile);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Upload lampiran baru
            $lampiran = [];
            foreach ($request->file('Lampiran') as $file) {
                $path = $file->store('public/lampiran');
                $lampiran[] = basename($path);
            }
        }

        // Update data di database
        $surat->update([
            'idJenis' => $data['idJenis'],
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
            'DiubahOleh' => auth()->user()->id, // Ubah dari DibuatOleh menjadi DiubahOleh
            'NamaFile' => $cekKategori->JenisSurat . '-' . $surat->id, // Gunakan ID surat yang sudah ada
        ]);

        // Path template & output
        $templatePath = storage_path('app/public/FormatSurat/' . $cekKategori->FormatSurat);
        $docxPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $surat->id . '.docx');
        $pdfPath = storage_path('app/public/surat/' . $cekKategori->JenisSurat . '-' . $surat->id . '.pdf');

        if (!file_exists($templatePath)) {
            return redirect()->route('drafter.index')->withErrors(['message' => 'Template tidak ditemukan']);
        }

        // Hapus file lama jika ada
        if (file_exists($docxPath)) {
            unlink($docxPath);
        }
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        // Buat dokumen Word dari template
        $templateProcessor = new TemplateProcessor($templatePath);

        // Ambil isi surat dari request (konten HTML)
        $isiSurat = $request->Isi;

        $NamaPenerima = User::with('getDepartmen')->where('id', $request->PenerimaSurat)->first();

        $datasurat = Surat::with('NamaPengirim')->find($surat->id); // Gunakan surat yang sedang diupdate
        $NamaCCInternal = $datasurat->CarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopy)->get() : null;
        $NamaCCExternal = $datasurat->CarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->CarbonCopyEks)->get() : null;
        $NamaBCCInternal = $datasurat->BlindCarbonCopy ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopy)->get() : null;
        $NamaBCCExternal = $datasurat->BlindCarbonCopyEks ? User::with('getDepartmen')->whereIn('id', $datasurat->BlindCarbonCopyEks)->get() : null;

        function formatUserList($users)
        {
            $output = [];
            foreach ($users as $user) {
                $output[] = $user->name . ' - ' . $user->getDepartmen->NamaDepartemen . ' - ' . $user->perusahaan;
            }
            return implode("\n", $output);
        }

        $formattedCCInternal = $NamaCCInternal ? formatUserList($NamaCCInternal) : null;
        $formattedCCExternal = $NamaCCExternal ? formatUserList($NamaCCExternal) : null;
        $formattedBCCInternal = $NamaBCCInternal ? formatUserList($NamaBCCInternal) : null;
        $formattedBCCExternal = $NamaBCCExternal ? formatUserList($NamaBCCExternal) : null;

        $writer = new PngWriter();
        $link = route('surat.digital', $surat->id);
        $qrCode = QrCode::create($link)
            ->setSize(100)
            ->setMargin(0);

        $barcode = $writer->write($qrCode)->getDataUri();

        // Khusus untuk isi surat, kita konversi HTML ke format Word
        $this->insertHtmlToTemplate($templateProcessor, 'isi', $isiSurat);

        $templateProcessor->setImageValue('Qrcode', $barcode);
        $dataWord = [
            'nomor' => $surat->NomorSurat ?? null,
            'kodeproyek' => $surat->NomorProject ?? null,
            'tanggalterbit' => $data['TanggalSurat'] ?? null,
            'penerima_int' => $NamaPenerima->name ?? null,
            'penerima_eks' => $NamaPenerima->name ?? null,
            'inisialpenerima' => $NamaPenerima->inisial ?? null,
            'jabatanpenerima' => $NamaPenerima->jabatan ?? null,
            'departpenerima' => $NamaPenerima->getDepartmen->NamaDepartemen ?? null,
            'perusahaanpenerima' => $data['PerusahaanInt'] ?? null,
            'alamat' => $data['AlamatInt'] ?? null,
            'Jabatan' => $NamaPenerima->jabatan ?? null,
            'email' => $NamaPenerima->email ?? null,
            'website' => $NamaPenerima->website ?? null,
            'perihal' => $request->Perihal ?? null,
            'pengirim' => $datasurat->NamaPengirim->name ?? null,
            'inisialpengirim' => $datasurat->NamaPengirim->inisial ?? null,
            'jabatpengirim' => $datasurat->NamaPengirim->jabatan ?? null,
            'departpengirim' => $datasurat->NamaPengirim->department ?? null,
            'perusahaanpengirim' => $datasurat->NamaPengirim->perusahaan ?? null,
            'ccint' => $formattedCCInternal ?? null,
            'ccxt' => $formattedCCExternal ?? null,
            'bccint' => $formattedBCCInternal ?? null,
            'bccext' => $formattedBCCExternal ?? null,
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
            'Qrcode' => $request->Qrcode ?? null,
            'Pengirim' => auth()->user()->name ?? null,
            'JabatanPengirim' => auth()->user()->jabatan ?? null,
            'Lampiran' => !empty($lampiran) ? implode(', ', array_map(fn($lampiran) => basename($lampiran), $lampiran)) : null,
        ];

        foreach ($dataWord as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        // Simpan dokumen
        $templateProcessor->saveAs($docxPath);

        // Konversi DOCX ke PDF menggunakan MPDF dengan perbaikan untuk mengatasi masalah halaman kosong dan jumlah halaman yang tidak sesuai
        // $phpWord = IOFactory::load($docxPath);
        // $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        // ob_start();
        // $htmlWriter->save('php://output');
        // $htmlContent = ob_get_clean();

        // $mpdf = new Mpdf();
        // $mpdf->WriteHTML($htmlContent);
        // $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);

        // Catat aktivitas
        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['Perihal' => $data['Perihal']])
            ->log('Mengupdate Surat dengan Nomor: "' . $surat->NomorSurat . '"');

        return redirect()->route('drafter.index')->with('success', 'Surat berhasil diupdate dalam format DOCX dan PDF.');
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

    private function GenerateKode($KodeProject)
    {
        // Ambil kode proyek dari tabel master proyek
        $proyek = KodeProyek::find($KodeProject);

        $kodeProyek = $proyek->Kode;
        $fixedCode = 'DRWCDE';
        $month = date('m');
        $year = date('Y');

        // Ambil surat terakhir di bulan dan tahun yang sama untuk proyek ini
        $lastSurat = Surat::where('KodeProject', $KodeProject)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSurat) {
            $lastId = (int) substr($lastSurat->id, -4);
            $nomor = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nomor = '0001';
        }

        $revisi = '00';
        $kodeSurat = $kodeProyek . '-' . $fixedCode . '-' . $nomor . '-' . $revisi;
        return $kodeSurat;
    }
    protected function insertHtmlToTemplate($templateProcessor, $placeholder, $htmlContent)
    {
        // Pastikan PhpWord dan library pendukung sudah diimpor
        // use PhpOffice\PhpWord\PhpWord;
        // use PhpOffice\PhpWord\Shared\Html;

        // Buat objek PHPWord baru
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Konversi HTML ke konten Word
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlContent, false, false);

        // Simpan sementara ke file XML
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'html_content');
        $xmlWriter->save($tempFile);

        // Ekstrak konten dari file XML
        $zip = new \ZipArchive();
        if ($zip->open($tempFile) === true) {
            $content = $zip->getFromName('word/document.xml');
            $zip->close();

            // Cari konten aktual (abaikan bagian header XML)
            $pattern = '/<w:body>(.*?)<w:sectPr>/s';
            if (preg_match($pattern, $content, $matches)) {
                $wordContent = $matches[1];

                // Set nilai ke dalam template sebagai kompleks (XML)
                $templateProcessor->setValue('${' . $placeholder . '}', $wordContent, 1);
            }
        }

        // Hapus file sementara
        @unlink($tempFile);
    }
}
