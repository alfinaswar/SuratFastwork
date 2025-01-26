@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Surat') }}</div>

                    <div class="card-body">
                        <form action="{{ route('drafter.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_surat">Kategori Surat</label>
                                        <select class="form-control" data-trigger name="idJenis"
                                            id="choices-multiple-default" placeholder="This is a placeholder">
                                            @foreach ($kategori as $i)
                                                <option value="{{ $i->id }}">{{ $i->JenisSurat }}</option>
                                            @endforeach
                                        </select>
                                        @error('idJenis')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_surat">Tanggal Surat</label>
                                        <input type="date" class="form-control" id="TanggalSurat" name="TanggalSurat">
                                        @error('TanggalSurat')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="lampiran">Lampiran</label>
                                <textarea class="form-control" id="Lampiran" name="Lampiran" rows="2" placeholder="Masukkan lampiran"></textarea>
                                @error('Lampiran')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="kepada">Penerima Surat</label>
                                <select class="form-control" data-trigger name="PenerimaSurat" id="choices-multiple-default"
                                    placeholder="This is a placeholder" multiple>
                                    @foreach ($penerima as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} - {{ $p->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('PenerimaSurat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="cc">Surat CC</label>
                                <select class="form-control" data-trigger name="CarbonCopy[]" id="choices-multiple-cc"
                                    placeholder="Pilih penerima CC" multiple>
                                    @foreach ($penerima as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} - {{ $p->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('CarbonCopy')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="bcc">Surat BC (Blind CC)</label>
                                <select class="form-control" data-trigger name="BlindCarbonCopy[]" id="choices-multiple-bcc"
                                    placeholder="Pilih penerima BC" multiple>
                                    @foreach ($penerima as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} - {{ $p->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('BlindCarbonCopy')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="perihal">Perihal</label>
                                <input type="text" class="form-control" id="Perihal" name="Perihal"
                                    placeholder="Perihal">
                                @error('Perihal')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="isi_surat">Isi Surat</label>
                                <textarea class="form-control" id="ckeditor-classic" name="Isi" rows="10" placeholder="Masukkan isi surat"></textarea>
                                @error('Isi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card-footer mt-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('drafter.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
