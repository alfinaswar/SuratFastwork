<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $countDraft = Surat::where('Status', 'Draft')->count();
        $countVerified = Surat::where('Status', 'Verified')->count();
        $countSent = Surat::where('Status', 'Sent')->count();
        $countTotal = Surat::all()->count();
        return view('home', compact('countDraft', 'countVerified', 'countSent', 'countTotal'));
    }

    public function showDigital($id)
    {
        $surat = Surat::with(['getPenerima', 'getPenulis', 'getCatatan' => function ($query) use ($id) {
            $query->where('DibuatOleh', auth()->user()->id)->where('idSurat', $id);
        }])->findOrFail($id);
        $Lampiran = json_decode($surat->Lampiran);
        $ambilCC = User::whereIn('id', $surat->CarbonCopy)->get();
        $ambilCCeks = User::whereIn('id', $surat->CarbonCopyEks)->get();
        $ambilBlindCC = User::whereIn('id', $surat->BlindCarbonCopy)->get();
        $ambilBlindCCEks = User::whereIn('id', $surat->BlindCarbonCopyEks)->get();
        $VerifiedBy = User::where('id', $surat->VerifiedBy)->first();
        $VerifiedBy = User::where('id', $surat->VerifiedBy)->first();
        $ApprovedBy = User::where('id', $surat->ApprovedBy)->first();
        $SentBy = User::where('id', $surat->SentBy)->first();
        $DibuatOleh = User::where('id', $surat->DibuatOleh)->first();

        $surat['CC'] = $ambilCC;
        $surat['CCEks'] = $ambilCCeks;
        $surat['CarbonCC'] = $ambilBlindCC;
        $surat['CarbonCCEks'] = $ambilBlindCCEks;
        $surat['FileLampiran'] = $Lampiran;
        $surat['VerifiedBy'] = $VerifiedBy;
        $surat['ApprovedBy'] = $ApprovedBy;
        $surat['SentBy'] = $SentBy;
        $surat['DibuatOleh'] = $DibuatOleh;
        // dd($surat);
        return view('detail', compact('surat'));
    }
}
