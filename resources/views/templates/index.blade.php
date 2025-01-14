@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Template</h1>
        <a href="{{ route('templates.create') }}" class="btn btn-success mb-3">Upload Template Baru</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($templates as $template)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $template->name }}</td>
                        <td>{{ $template->description }}</td>
                        <td><a href="{{ asset('storage/' . $template->file_path) }}" target="_blank">Download</a></td>
                        <td>
                            <!-- Tambahkan tombol edit/delete jika diperlukan -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
