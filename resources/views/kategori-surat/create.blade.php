<!-- resources/views/kategori-surat/create.blade.php -->
@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ $message }}',
            });
        </script>
    @endif
    <div class="row">
        <div class="col-xl-12 col-lg-8">
            <div class="card card-bx m-b30">
                <div class="card-header">
                    <h6 class="title">Tambah Jenis Surat Baru</h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('kategori-surat.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Kode Surat</label><small class="text-danger"> Contoh <b>Surat
                                        Keputusan Direksi</b> Menjadi <b>SKDIR</b></small>
                                <input type="text" name="idJenis" class="form-control" placeholder="Masukkan Kode Surat"
                                    value="{{ old('idJenis') }}">
                                @error('idJenis')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Jenis Surat</label>
                                <input type="text" name="JenisSurat" class="form-control"
                                    placeholder="Masukkan Jenis Surat" value="{{ old('JenisSurat') }}">
                                @error('JenisSurat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Aktif</label>
                                <select name="Aktif" class="form-control">
                                    <option value="">Pilih Status</option>
                                    <option value="Y" {{ old('Aktif') == 'Y' ? 'selected' : '' }}>Ya</option>
                                    <option value="N" {{ old('Aktif') == 'N' ? 'selected' : '' }}>Tidak</option>
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
