@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Tambah Kode Proyek</div>

        <div class="card-body">
            <form action="{{ route('master-proyek.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="Kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" id="Kode" name="Kode" placeholder="Masukkan Kode" required>
                </div>

                <div class="mb-3">
                    <label for="Nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master-proyek.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
