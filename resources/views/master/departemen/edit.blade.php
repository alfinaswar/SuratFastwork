@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Edit Departemen</div>

        <div class="card-body">
            <form action="{{ route('master-departemen.update', $departemen->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="Kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" id="Kode" name="Kode" value="{{ $departemen->Kode }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="NamaDepartemen" class="form-label">Nama Departemen</label>
                    <input type="text" class="form-control" id="NamaDepartemen" name="NamaDepartemen"
                        value="{{ $departemen->NamaDepartemen }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('master-departemen.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
