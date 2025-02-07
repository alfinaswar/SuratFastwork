<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::with('NamaPengirim')->where('Status', 'Sent')->where('PenerimaSurat', auth()->user()->id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('surat-masuk.show', $row->id) . '" class="btn btn-info btn-md btn-show" title="Show"><i class="fas fa-eye"></i></a>';
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
        return view('surat-masuk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function read($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->Status = 'Read';
        $surat->save();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['status' => 'Read'])
            ->log('Telah Membaca Surat Dengan Nomor ' . $surat->NomorSurat);

        return redirect()->route('surat-masuk.show', $id)->with('success', 'Surat telah ditandai sebagai telah dibaca');
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
        $surat = Surat::with([
            'getPenerima',
            'getPenulis',
            'getCatatan' => function ($query) use ($id) {
                $query->where('DibuatOleh', auth()->user()->id)->where('idSurat', $id);
            }
        ])->findOrFail($id);
        $Lampiran = json_decode($surat->Lampiran);
        $ambilCC = User::whereIn('id', $surat->CarbonCopy)->get();
        $ambilBlindCC = User::whereIn('id', $surat->BlindCarbonCopy)->get();
        $surat['CC'] = $ambilCC;
        $surat['CarbonCC'] = $ambilBlindCC;
        $surat['FileLampiran'] = $Lampiran;
        // dd($surat);
        return view('surat-masuk.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        //
    }
}
