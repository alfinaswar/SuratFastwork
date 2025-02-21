@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Edit Kode Proyek</div>

        <div class="card-body">
            <form action="{{ route('master-proyek.update', $kodeProyek->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="Kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" id="Kode" name="Kode" value="{{ $kodeProyek->Kode }}" required>
                </div>

                <div class="mb-3">
                    <label for="Nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="Nama" name="Nama" value="{{ $kodeProyek->Nama }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('master-proyek.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
