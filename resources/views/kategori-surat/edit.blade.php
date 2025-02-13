<!-- resources/views/kategori-surat/edit.blade.php -->
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
                    <h6 class="title">Edit Jenis Surat</h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('kategori-surat.update', $masterJenis->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Kode Surat</label><small class="text-danger"> Contoh <b>Surat
                                        Keputusan Direksi</b> Menjadi <b>SKDIR</b></small>
                                <input type="text" name="idJenis" class="form-control" placeholder="Masukkan Kode Surat"
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
                                    <option value="">Pilih Status</option>
                                    <option value="Y" {{ old('Aktif', $masterJenis->Aktif) == 'Y' ? 'selected' : '' }}>
                                        Ya</option>
                                    <option value="N" {{ old('Aktif', $masterJenis->Aktif) == 'N' ? 'selected' : '' }}>
                                        Tidak</option>
                                </select>
                                @error('Aktif')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 m-b30">
                                <label class="form-label">Format Surat</label>
                                <input type="file" name="FormatSurat" class="form-control" accept=".docx, .doc, .pdf">
                                @error('FormatSurat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-8">
            <div class="card card-bx m-b30">
                <div class="card-header">
                    <h6 class="title">Field Surat / Dokumen</h6>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">Penerima Internal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="PenerimaInternal" id="YA"
                                    value="YA"
                                    {{ old('PenerimaInternal', $masterJenis->getField->PenerimaInternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="PenerimaInternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('PenerimaInternal', $masterJenis->getField->PenerimaInternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('PenerimaInternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika penerima internal di ceklis, maka akan memiliki field: Penerima
                                internal, Kode inisial penerima, Jabatan penerima, Departemen penerima,
                                Perusahaan penerima, Alamat penerima, Surel penerima, Website penerima</small>
                        </div>

                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">Penerima Eksternal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="PenerimaEksternal" id="YA"
                                    value="YA"
                                    {{ old('PenerimaEksternal', $masterJenis->getField->PenerimaEksternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="PenerimaEksternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('PenerimaEksternal', $masterJenis->getField->PenerimaEksternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('PenerimaEksternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika penerima Eksternal di ceklis, maka akan memiliki field: Penerima
                                eksternal, Kode inisial penerima, Jabatan penerima, Departemen penerima,
                                Perusahaan penerima, Alamat penerima, Surel penerima, Website penerima</small>
                        </div>

                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">CC Internal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="CCInternal" id="YA"
                                    value="YA"
                                    {{ old('CCInternal', $masterJenis->getField->CCInternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="CCInternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('CCInternal', $masterJenis->getField->CCInternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('CCInternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika CC internal di ceklis, maka akan memiliki field: CC internal,
                                Kode inisial CC, Jabatan CC, Departemen CC, Perusahaan CC</small>
                        </div>

                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">CC Eksternal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="CCEksternal" id="YA"
                                    value="YA"
                                    {{ old('CCEksternal', $masterJenis->getField->CCEksternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="CCEksternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('CCEksternal', $masterJenis->getField->CCEksternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('CCEksternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika CC eksternal di ceklis, maka akan memiliki field: CC eksternal,
                                Kode inisial CC, Jabatan CC, Departemen CC, Perusahaan CC</small>
                        </div>
                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">BCC Internal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="BCCInternal" id="YA"
                                    value="YA"
                                    {{ old('BCCInternal', $masterJenis->getField->BCCInternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="BCCInternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('BCCInternal', $masterJenis->getField->BCCInternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('BCCInternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika BCC internal di ceklis, maka akan memiliki field: BCC internal,
                                Kode inisial BCC, Jabatan BCC, Departemen BCC, Perusahaan BCC</small>
                        </div>
                        <div class="col-sm-6 m-b30 mt-3">
                            <label class="form-label">BCC Eksternal</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="BCCEksternal" id="YA"
                                    value="YA"
                                    {{ old('BCCEksternal', $masterJenis->getField->BCEksternal) == 'YA' ? 'checked' : '' }}>
                                <label class="form-check-label" for="YA">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="BCCEksternal" id="TIDAK"
                                    value="TIDAK"
                                    {{ old('BCCEksternal', $masterJenis->getField->BCEksternal) == 'TIDAK' ? 'checked' : '' }}>
                                <label class="form-check-label" for="TIDAK">
                                    Tidak
                                </label>
                            </div>
                            @error('BCCEksternal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-info">Jika BCC eksternal di ceklis, maka akan memiliki field: BCC eksternal,
                                Kode inisial BCC, Jabatan BCC, Departemen BCC, Perusahaan BCC</small>
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
