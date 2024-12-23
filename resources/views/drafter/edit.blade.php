@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Surat') }}</div>

                <div class="card-body">
                    <form action="{{ route('drafter.update', $surat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="idJenis">Kategori Surat</label>
                                    <select class="form-control" data-trigger name="idJenis" id="choices-multiple-default"
                                        placeholder="This is a placeholder" multiple>
                                        @foreach ($kategori as $i)
                                            <option value="{{ $i->id }}"
                                                @if ($i->id == $surat->idJenis) Selected @endif>
                                                {{ $i->JenisSurat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idJenis')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="TanggalSurat">Tanggal Surat</label>
                                    <input type="date" class="form-control" id="TanggalSurat" name="TanggalSurat"
                                        value="{{ $surat->TanggalSurat }}" required>
                                    @error('TanggalSurat')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="Lampiran">Lampiran</label>
                            <textarea class="form-control" id="Lampiran" name="Lampiran" rows="2">{{ $surat->Lampiran }}</textarea>
                            @error('Lampiran')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="PenerimaSurat">Penerima Surat</label>

                            <select class="form-control" data-trigger name="PenerimaSurat" id="choices-multiple-default"
                                placeholder="This is a placeholder" multiple>
                                @foreach ($penerima as $p)
                                    <option value="{{ $p->id }}"
                                        {{ is_array($surat->PenerimaSurat) && in_array($p->id, $surat->PenerimaSurat) ? 'selected' : '' }}>
                                        {{ $p->name }} - {{ $p->jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('PenerimaSurat')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="CarbonCopy">Surat CC</label>
                            <select class="form-control" name="CarbonCopy[]" data-trigger id="choices-multiple-default"
                                multiple>
                                @foreach ($penerima as $p)
                                    <option value="{{ $p->id }}"
                                        {{ is_array($surat->CarbonCopy) && in_array($p->id, $surat->CarbonCopy) ? 'selected' : '' }}>
                                        {{ $p->name }} - {{ $p->jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('CarbonCopy')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="BlindCarbonCopy">Surat BC (Blind CC)</label>
                            <select class="form-control" name="BlindCarbonCopy[]" data-trigger
                                id="choices-multiple-default"multiple>
                                @foreach ($penerima as $p)
                                    <option value="{{ $p->id }}"
                                        {{ is_array($surat->BlindCarbonCopy) && in_array($p->id, $surat->BlindCarbonCopy) ? 'selected' : '' }}>
                                        {{ $p->name }} - {{ $p->jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('BlindCarbonCopy')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="Perihal">Perihal</label>
                            <input type="text" class="form-control" id="Perihal" name="Perihal"
                                value="{{ $surat->Perihal }}" required>
                            @error('Perihal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="Isi">Isi Surat</label>
                            <textarea class="form-control" id="Isi" name="Isi" rows="10" required>{!! $surat->Isi !!}</textarea>
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
@endsection
