<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::all();  // Mengambil semua template
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
        $template = Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $file_path,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($template)
            ->log('Mengunggah Template: ' . $template->name);

        return redirect()->route('templates.index')->with('success', 'Template berhasil diunggah.');
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|mimes:doc,docx|max:2048',
        ]);

        // Temukan template berdasarkan ID
        $template = Template::findOrFail($id);
        $template->name = $request->name;
        $template->description = $request->description;
        if ($request->hasFile('file')) {
            if ($template->file_path && \Storage::exists('public/file/' . $template->file_path)) {
                \Storage::delete('public/file/' . $template->file_path);
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/file', $fileName);
            $template->file_path = $fileName;
        }

        $template->save();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($template)
            ->log('Mengupdate Template: ' . $template->name);

        return redirect()->route('templates.index')->with('success', 'Template berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $template = Template::find($id);
        if (!$template) {
            return response()->json(['message' => 'Template tidak ditemukan'], 404);
        }
        if ($template->file_path && \Storage::exists('public/file/' . $template->file_path)) {
            \Storage::delete('public/file/' . $template->file_path);
            activity()
                ->causedBy(auth()->user())
                ->performedOn($template)
                ->log('Menghapus Template: ' . $template->name);
        }
        $template->delete();
        return response()->json(['message' => 'Template berhasil dihapus'], 200);
    }
}
