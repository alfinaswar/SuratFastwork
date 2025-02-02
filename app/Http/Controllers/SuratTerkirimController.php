<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratTerkirim;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class SuratTerkirimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::where('Status', 'Sent')->where('SentBy', auth()->user()->id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('surat-terkirim.download', $row->id) . '" class="btn btn-info btn-md btn-show" title="Show"><i class="fas fa-download"></i></a>';
                    return $btnShow;
                })
                ->addColumn('StatusLabel', function ($row) {
                    switch ($row->Status) {
                        case 'Draft':
                            $StatusLabel = '<span class="badge bg-warning"><i class="fas fa-edit"></i> Draft</span>';
                            break;
                        case 'Verified':
                            $StatusLabel = '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Telah Diverifikasi</span>';
                            break;
                        case 'Approved':
                            $StatusLabel = '<span class="badge bg-info"><i class="fas fa-check-circle"></i> Telah Disetujui</span>';
                            break;
                        case 'Revision':
                            $StatusLabel = '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Revisi</span>';
                            break;
                        case 'Sent':
                            $StatusLabel = '<span class="badge bg-primary"><i class="fas fa-paper-plane"></i> Telah Dikirim</span>';
                            break;
                        case 'Received':
                            $StatusLabel = '<span class="badge bg-secondary"><i class="fas fa-inbox"></i> Telah Diterima</span>';
                            break;
                        case 'Read':
                            $StatusLabel = '<span class="badge bg-light"><i class="fas fa-eye"></i> Telah Dibaca</span>';
                            break;
                        default:
                            $StatusLabel = '<span class="badge bg-dark"><i class="fas fa-question-circle"></i> Tidak Diketahui</span>';
                    }
                    return $StatusLabel;
                })
                ->rawColumns(['action', 'StatusLabel'])
                ->make(true);
        }
        return view('surat-terkirim.index');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surat = Surat::with(['getPenerima', 'getPenulis', 'getCatatan' => function ($query) use ($id) {
            $query->where('DibuatOleh', auth()->user()->id)->where('idSurat', $id);
        }])->findOrFail($id);
        // dd($surat);
        return view('surat-terkirim.show', compact('surat'));
    }

    public function download($id)
    {
        $surat = Surat::with(['getPenerima', 'getPenulis'])->findOrFail($id);

        // cek folder penyimpanan zip
        $zipDir = storage_path('app/public/berkas/');
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0775, true);
        }

        // Nama dan path file ZIP
        $zipName = 'surat_' . $surat->id . '.zip';
        $zipPath = $zipDir . $zipName;

        // Buat file ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            return back()->withErrors(['message' => 'Gagal membuat file ZIP']);
        }

        // Tambahkan file surat utama (Word/PDF)
        $fileSuratPath = storage_path('app/public/surat/' . $surat->NamaFile . '.docx');  // Bisa juga PDF
        if (file_exists($fileSuratPath)) {
            $zip->addFile($fileSuratPath, basename($fileSuratPath));
        }

        // Tambahkan lampiran kalo ada
        $lampiranArray = json_decode($surat->Lampiran, true) ?? [];
        // dd($lampiranArray);
        if (!empty($lampiranArray)) {
            foreach ($lampiranArray as $lampiran) {
                $lampiranPath = storage_path('app/public/lampiran/' . $lampiran);
                if (file_exists($lampiranPath)) {
                    $zip->addFile($lampiranPath, 'lampiran/' . basename($lampiran));
                }
            }
        }
        $zip->close();

        // download dan hapus setelah terkirim
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratTerkirim $suratTerkirim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratTerkirim $suratTerkirim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratTerkirim $suratTerkirim)
    {
        //
    }
}
