<?php

namespace App\Http\Controllers;

use App\Models\Surat;
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('persetujuan.index');
    }
    public function show($id)
    {
        $surat = Surat::with('getPenerima', 'getPenulis')->findOrFail($id);
        return view('persetujuan.show', compact('surat'));
    }
}
