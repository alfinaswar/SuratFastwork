@extends('layouts.app')

@section('content')
    <style>
        .preview-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .preview-item img {
            display: block;
            margin-bottom: 5px;
        }
    </style>

    <div class="row">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <form action="{{ route('drafter.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">

                            <div class="row align-items-center">
                                <div class="col-md-6 ">
                                    <h4 class="card-title">EDIT DRAFT SURAT</h4>
                                    <p class="fw-bold">Verifikator :
                                        {{ $surat->getVerifikator->name ?? 'Belum Di Cek Oleh Verifikator' }}
                                        di verifikasi pada {{ $surat->VerifiedAt }}</p>
                                    <div class="row">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Lihat
                                            Revisi</button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_surat">Kategori Surat</label>
                                            <select class="form-control" data-trigger name="idJenis"
                                                id="choices-multiple-default KategoriSurat"
                                                placeholder="This is a placeholder" onchange="changeKategori(this)">
                                                <option value="">Pilih Kategori Surat</option>
                                                @foreach ($kategori as $i)
                                                    <option value="{{ $i->id }}"
                                                        @if ($surat->idJenis == $i->id) selected @endif>
                                                        {{ $i->JenisSurat }}</option>
                                                @endforeach
                                            </select>
                                            @error('idJenis')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_surat">Kode Project</label>
                                            <select class="form-control" data-trigger name="KodeProject" id="choices-multiple-default"
                                                placeholder="This is a placeholder">
                                                <option value="">Pilih Kode Project</option>
                                                @foreach ($KodeProject as $p)
                                                    <option value="{{ $p->id }}" @if ($surat->KodeProject == $p->id) selected @endif>
                                                        {{ $p->Kode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('KodeProject')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="accordion" id="accordionExample">

                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="perihal">Perihal</label>
                                        <input type="text" class="form-control" id="Perihal" name="Perihal"
                                            placeholder="Perihal" value="{{ $surat->Perihal }}">
                                        @error('Perihal')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_surat">Tanggal Surat</label>
                                            <input type="date" class="form-control" id="TanggalSurat" name="TanggalSurat"
                                                value="{{ $surat->TanggalSurat }}">
                                            @error('TanggalSurat')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="file" class="form-control" id="Lampiran" name="Lampiran[]" multiple
                                        onchange="previewFiles(this)">
                                    @error('Lampiran')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Preview container -->
                                    <div id="preview-container" class="mt-3 d-flex flex-wrap gap-2"></div>
                                </div>
                                @if ($surat->PenerimaSurat)
                                    <div class="accordion-item" id="PenerimaInternal">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button fw-medium" type="button" aria-expanded="true"
                                                aria-controls="collapseThree">
                                                Penerima Internal
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse show"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Penerima Surat</label>
                                                            <select class="form-control" data-trigger name="PenerimaSurat"
                                                                id="choices-multiple-default PenerimaSurat"
                                                                onchange="selectPenerimaInt(this)"
                                                                placeholder="This is a placeholder">
                                                                <option>Pilih Penerima</option>
                                                                @foreach ($penerima as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        @if ($surat->PenerimaSurat == $p->id) selected @endif>
                                                                        {{ $p->name }} -
                                                                        {{ $p->jabatan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('PenerimaSurat')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Inisial Penerima</label>
                                                            <input type="text" name="InisialPenerima"
                                                                id="InisialPenerima" class="form-control"
                                                                placeholder="Autofill" readonly
                                                                value="{{ $surat->InisialPenerima }}">

                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Jabatan Penerima</label>
                                                            <input type="text" name="JabatanInt" id="JabatanInt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->JabatanInt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Departemen Penerima</label>
                                                            <input type="text" name="DepartemenInt" id="DepartemenInt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->DepartemenInt }}">

                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Perusahaan Penerima</label>
                                                            <input type="text" name="PerusahaanInt" id="PerusahaanInt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->PerusahaanInt }}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Alamat Penerima</label>
                                                            <input type="text" name="AlamatInt" id="AlamatInt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->AlamatInt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Email Penerima</label>
                                                            <input type="text" name="EmailInt" id="EmailInt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->EmailInt }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($surat->PenerimaSuratEks)
                                    <div class="accordion-item" id="PenerimaEksternal">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button fw-medium" type="button"
                                                data-bs-target="#collapseFour" aria-expanded="true"
                                                aria-controls="collapseFour">
                                                Penerima Eksternal
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse show"
                                            aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Penerima Surat</label>
                                                            <select class="form-control" data-trigger
                                                                name="PenerimaSuratEksternal"
                                                                id="choices-multiple-default"
                                                                onchange="selectPenerimaExt(this)"
                                                                placeholder="This is a placeholder">
                                                                <option value="">Pilih Penerima</option>
                                                                @foreach ($eksternal as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        @if ($surat->PenerimaSuratEksternal == $p->id) selected @endif>
                                                                        {{ $p->Nama }} -
                                                                        {{ $p->Jabatan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('PenerimaSuratEksternal')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Inisial Penerima</label>
                                                            <input type="text" name="InisialPenerima"
                                                                id="InisialPenerimaExt" class="form-control"
                                                                placeholder="Autofill" readonly
                                                                value="{{ $surat->InisialPenerimaExt }}">

                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Jabatan Penerima</label>
                                                            <input type="text" name="JabatanExt" id="JabatanExt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->JabatanExt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Departemen Penerima</label>
                                                            <input type="text" name="DepartemenExt" id="DepartemenExt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->DepartemenExt }}">

                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Perusahaan Penerima</label>
                                                            <input type="text" name="PerusahaanExt" id="PerusahaanExt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->PerusahaanExt }}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Alamat Penerima</label>
                                                            <input type="text" name="AlamatExt" id="AlamatExt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->AlamatExt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="kepada">Email Penerima</label>
                                                            <input type="text" name="EmailExt" id="EmailExt"
                                                                class="form-control" placeholder="Autofill" readonly
                                                                value="{{ $surat->EmailExt }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($surat->CarbonCopy)
                                    <div class="accordion-item" id="CCInternal">
                                        <h2 class="accordion-header" id="headingFive">
                                            <button class="accordion-button fw-medium" type="button"
                                                data-bs-target="#collapseFive" aria-expanded="true"
                                                aria-controls="collapseFive">
                                                CC Internal
                                            </button>
                                        </h2>
                                        <div id="collapseFive" class="accordion-collapse collapse show"
                                            aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="form-group mb-3">
                                                    <label for="cc">Carbon Copy Internal</label>
                                                    <select class="form-control" data-trigger name="CarbonCopy[]"
                                                        id="choices-multiple-cc" placeholder="Pilih penerima CC" multiple>
                                                        <option value="">Pilih penerima CC</option>
                                                        @foreach ($penerima as $p)
                                                            <option value="{{ $p->id }}"
                                                                @if (in_array($p->id, $surat->CarbonCopy)) selected @endif>
                                                                {{ $p->name }} -
                                                                {{ $p->jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('CarbonCopy')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($surat->CarbonCopyEks)
                                                                        <div class="accordion-item" id="CCExternal">
                                                                            <h2 class="accordion-header" id="headingSix">
                                                                                <button class="accordion-button fw-medium" type="button"
                                                                                    data-bs-target="#collapseSix" aria-expanded="true"
                                                                                    aria-controls="collapseSix">
                                                                                    Carbon Copy Eksternal
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseSix" class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                                                <div class="accordion-body">
                                                                                    <div class="form-group mb-3">
                                                                                        <label for="cc">Carbon Copy Eksternal</label>
                                                                                        <select class="form-control" data-trigger name="CarbonCopyExt[]"
                                                                                            id="choices-multiple-cc" placeholder="Pilih penerima CC" multiple>
                                                                                            <option value="">Pilih penerima</option>
                                    @foreach ($eksternal as $p)
                                        <option value="{{ $p->id }}" @if ($surat->CarbonCopyEks == $p->id) selected @endif>
                                            {{ $p->Nama }} -
                                            {{ $p->Jabatan }}
                                        </option>
                                    @endforeach
                                                                                        </select>
                                                                                        @error('CarbonCopy')
                                                                                            <div class="text-danger">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                @endif
                                @if ($surat->BlindCarbonCopy)
                                    <div class="accordion-item" id="BCCInternal">
                                        <h2 class="accordion-header" id="headingSeven">
                                            <button class="accordion-button fw-medium" type="button"
                                                data-bs-target="#collapseSeven" aria-expanded="true"
                                                aria-controls="collapseSeven">
                                                BCC Internal
                                            </button>
                                        </h2>
                                        <div id="collapseSeven" class="accordion-collapse collapse show"
                                            aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="form-group mb-3">
                                                    <label for="cc">Blind Carbon Copy Internal</label>
                                                    <select class="form-control" data-trigger name="BlindCarbonCopyInt[]"
                                                        id="choices-multiple-cc" placeholder="Pilih penerima CC" multiple>
                                                        <option value="">Pilih penerima</option>
                                                        @foreach ($penerima as $p)
                                                            <option value="{{ $p->id }}"
                                                                @if (in_array($p->id, $surat->BlindCarbonCopy)) selected @endif>
                                                                {{ $p->name }} -
                                                                {{ $p->jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('CarbonCopy')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($surat->BlindCarbonCopyEks)
                                    <div class="accordion-item" id="BCCExternal">
                                        <h2 class="accordion-header" id="headingEight">
                                            <button class="accordion-button fw-medium" type="button"
                                                data-bs-target="#collapseEight" aria-expanded="true"
                                                aria-controls="collapseEight">
                                                BCC Eksternal
                                            </button>
                                        </h2>
                                        <div id="collapseEight" class="accordion-collapse collapse show"
                                            aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="form-group mb-3">
                                                    <label for="bcc">Blind CC Eksternal</label>
                                                    <select class="form-control" data-trigger name="BlindCarbonCopyExt[]"
                                                        id="choices-multiple-bcc" placeholder="Pilih penerima BC"
                                                        multiple>
                                                        <option value="">Pilih penerima</option>
                                                        @foreach ($eksternal as $p)
                                                            <option value="{{ $p->id }}" @if ($surat->BlindCarbonCopyEks == $p->id) selected @endif>
                                                                {{ $p->Nama }} -
                                                                {{ $p->Jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('BlindCarbonCopy')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                                <div class="accordion-item" id="IsiSuratCC">
                                    <h2 class="accordion-header" id="headingEight">
                                        <button class="accordion-button fw-medium" type="button"
                                            data-bs-target="#collapseEight" aria-expanded="true"
                                            aria-controls="collapseEight">
                                            Isi Surat
                                        </button>
                                    </h2>
                                    <div id="collapseEight" class="accordion-collapse collapse show"
                                        aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="form-group mb-3">
                                                <label for="isi_surat">Isi Surat</label>
                                                <textarea class="form-control" id="ckeditor-classic" name="Isi" rows="10"
                                                    placeholder="Masukkan isi surat">{{ $surat->Isi }}</textarea>
                                                @error('Isi')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer mt-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('drafter.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </div><!-- end accordion -->
                    </form>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->

    </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Catatan Revisi</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <div class="row mb-4 fw-bold fs-6">
                <div class="col-md-6">
                    Deverifikasi Oleh {{ $surat->getVerifikator->name ?? 'Belum Di Cek Oleh Verifikator' }}
                </div>
                <div class="col-md-6">
                    Deverifikasi Pada <br>{{ $surat->VerifiedAt }}
                </div>
            </div>
            {!! $surat->getCatatan->Catatan ?? 'Belum ada Catatan dari Verifikator' !!}
        </div>
    </div>
@endsection
@push('js')
    <script>
        function selectPenerimaInt(data) {

            var idPenerima = data.value;
            $.ajax({
                type: "GET",
                url: "{{ route('users.get-users', '') }}/" + idPenerima,
                data: {},
                dataType: "json",
                success: function(response) {
                    $("#InisialPenerima").val(response.inisial);
                    $("#JabatanInt").val(response.jabatan);
                    $("#DepartemenInt").val(response.get_departmen.NamaDepartemen);
                    $("#PerusahaanInt").val(response.perusahaan);
                    $("#AlamatInt").val(response.alamat);
                    $("#EmailInt").val(response.email);

                }
            });
        }

        function selectPenerimaExt(data) {

            var idPenerima = data.value;
            $.ajax({
                type: "GET",
                url: "{{ route('users.get-users', '') }}/" + idPenerima,
                data: {},
                dataType: "json",
                success: function(response) {
                    $("#InisialPenerimaExt").val(response.inisial);
                    $("#JabatanExt").val(response.jabatan);
                    $("#DepartemenExt").val(response.get_departmen.NamaDepartemen);
                    $("#PerusahaanExt").val(response.perusahaan);
                    $("#AlamatExt").val(response.alamat);
                    $("#EmailExt").val(response.email);

                }
            });
        }
    </script>
    <script>
        function previewFiles(input) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const previewElement = document.createElement('div');
                previewElement.className = 'preview-item position-relative';

                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewElement.innerHTML = `
                                    <img src="${e.target.result}" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    <div class="mt-1">${file.name}</div>
                                `;
                    } else {
                        let fileIcon = '📄';
                        if (file.type.includes('pdf')) fileIcon = '📕';
                        else if (file.type.includes('word')) fileIcon = '📘';
                        else if (file.type.includes('excel') || file.type.includes('sheet')) fileIcon = '📗';

                        previewElement.innerHTML = `
                                    <div class="text-center">
                                        <div style="font-size: 2rem;">${fileIcon}</div>
                                        <div style="word-break: break-word; max-width: 150px;">${file.name}</div>
                                    </div>
                                `;
                    }
                };

                if (file.type.startsWith('image/')) {
                    reader.readAsDataURL(file);
                } else {
                    reader.readAsText(file);
                }

                previewContainer.appendChild(previewElement);
            });
        }
    </script>
@endpush
