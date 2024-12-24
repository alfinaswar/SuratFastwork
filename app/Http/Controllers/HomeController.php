<?php

namespace App\Http\Controllers;

use App\Models\Surat;
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
}
