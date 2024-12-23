@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-8">
            <div class="card card-bx m-b30">
                <div class="card-header">
                    <h6 class="title">Edit Jenis Surat</h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('kategori-surat.update', $masterJenis->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Kode Surat</label>
                                <input type="text" name="idJenis" class="form-control"
                                    placeholder="Masukkan Kode Jenis Surat"
                                    value="{{ old('idJenis', $masterJenis->idJenis) }}">
                                @error('idJenis')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Jenis Surat</label>
                                <input type="text" name="JenisSurat" class="form-control"
                                    placeholder="Masukkan Jenis Surat"
                                    value="{{ old('JenisSurat', $masterJenis->JenisSurat) }}">
                                @error('JenisSurat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Aktif</label>
                                <select name="Aktif" class="form-control">
                                    <option value="Y" {{ old('Aktif', $masterJenis->Aktif) == 'Y' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="N" {{ old('Aktif', $masterJenis->Aktif) == 'N' ? 'selected' : '' }}>
                                        Tidak Aktif</option>
                                </select>
                                @error('Aktif')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kategori-surat.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
