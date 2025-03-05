<?php

namespace App\Http\Controllers;

use App\Models\CatatanSurat;
use App\Models\Surat;
use App\Models\User;
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
            $data = Surat::where('Status', 'Submited')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('verifikator.show', $row->id) . '" class="btn btn-info btn-md btn-show" title="Show"><i class="fas fa-eye"></i></a>';
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
                        case 'Submited':
                            $StatusLabel = '<span class="badge bg-primary"><i class="fas fa-paper-plane"></i> Baru Diajukan</span>';
                            break;
                        default:
                            $StatusLabel = '<span class="badge bg-dark"><i class="fas fa-question-circle"></i> Tidak Diketahui</span>';
                    }
                    return $StatusLabel;
                })
                ->rawColumns(['action', 'StatusLabel'])
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
            'VerifiedBy' => auth()->user()->id,
            'VerifiedAt' => now(),
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
            ->log('Status dengan nomor: ' . $surat->NomorSurat . ' surat telah di ' . $data['Status']);

        return redirect()->route('verifikator.index')->with('success', 'Status Surat Berhasil Diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surat = Surat::with([
            'getPenerima',
            'getPenulis',
            'getCatatan' => function ($query) use ($id) {
                $query->where('DibuatOleh', auth()->user()->id)->where('idSurat', $id);
            }
        ])->findOrFail($id);
        // dd($surat);
        return view('verifikator.show', compact('surat'));
    }
    public function showPreview($id)
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
        // dd($surat);
        return view('verifikator.show-preview', compact('surat'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function downloadPreview($id)
    {
        $surat = Surat::with('getPenerima', 'getPenulis')->findOrFail($id);
        // dd($surat);
        $file = storage_path('app/public/surat/' . $surat->NamaFile . '.docx');
        if (file_exists($file)) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($surat)
                ->withProperties(['file' => $file])
                ->log('Mengunduh pratinjau surat: ' . $surat->NomorSurat);
            return response()->download($file, $surat->NamaFile . '.docx');
        } else {
            abort(404, 'File tidak ditemukan.');
        }
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
