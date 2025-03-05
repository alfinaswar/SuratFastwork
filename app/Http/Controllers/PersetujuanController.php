<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Surat::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('persetujuan-surat.show', $row->id) . '" class="btn btn-info btn-md btn-show" title="Show"><i class="fas fa-eye"></i></a>';
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
        return view('persetujuan.index');
    }

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
        if ($surat->BlindCarbonCopy != null) {
            $ambilBlindCC = User::whereIn('id', $surat->BlindCarbonCopy)->get();
        } else {
            $ambilBlindCC = null;
        }
        $surat['CC'] = $ambilCC;
        $surat['CarbonCC'] = $ambilBlindCC;
        $surat['FileLampiran'] = $Lampiran;
        // dd($surat);
        return view('persetujuan.show', compact('surat'));

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
        return view('persetujuan.show-surat', compact('surat'));
    }

    public function approve($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->Status = 'Sent';
        $surat->ApprovedAt = now();
        $surat->ApprovedBy = auth()->user()->id;
        $surat->SentBy = auth()->user()->id;
        $surat->SentAt = now();
        $surat->save();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($surat)
            ->withProperties(['status' => 'Sent'])
            ->log('Dokumen telah disetujui dan dikirim ke penerima');

        return redirect()->route('persetujuan-surat.index')->with('success', 'Surat Dengan Nomor ' . $surat->NomorSurat . ' telah disetujui dan dikirim ke penerima');
    }
}
