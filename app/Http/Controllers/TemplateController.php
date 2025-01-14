<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::all(); // Mengambil semua template
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file->storeAs('public/file', $file->getClientOriginalName());
            $file_path = $file->getClientOriginalName();
        } else {
            $file_path = null;
        }
        Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $file_path,
        ]);

        return redirect()->route('templates.index')->with('success', 'Template berhasil diunggah.');
    }
}
