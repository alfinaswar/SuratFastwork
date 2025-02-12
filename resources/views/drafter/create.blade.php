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
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title">BUAT DRAFT SURAT</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_surat">Kategori Surat</label>
                                        <select class="form-control" data-trigger name="idJenis"
                                            id="choices-multiple-default KategoriSurat" placeholder="This is a placeholder"
                                            onchange="changeKategori(this)">
                                            @foreach ($kategori as $i)
                                                <option value="{{ $i->id }}">{{ $i->JenisSurat }}</option>
                                            @endforeach
                                        </select>
                                        @error('idJenis')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="accordion" id="accordionExample">

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button fw-medium" type="button" aria-expanded="true"
                                        aria-controls="collapseThree">
                                        Penerima Internal
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse show"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">Penerima Internal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button fw-medium" type="button" data-bs-target="#collapseFour"
                                        aria-expanded="true" aria-controls="collapseFour">
                                        Penerima Eksternal
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse show"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">Penerima Eksternal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button fw-medium" type="button" data-bs-target="#collapseFive"
                                        aria-expanded="true" aria-controls="collapseFive">
                                        CC Internal
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse show"
                                    aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">CC Internal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button fw-medium" type="button" data-bs-target="#collapseSix"
                                        aria-expanded="true" aria-controls="collapseSix">
                                        CC Eksternal
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">CC Eksternal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button fw-medium" type="button"
                                        data-bs-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                        BCC Internal
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse show"
                                    aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <strong class="text-dark">BCC Internal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
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
                                        <div class="text-muted">
                                            <strong class="text-dark">BCC Eksternal.</strong> It
                                            is hidden by default, until the collapse plugin adds the appropriate classes
                                            that we use to style each element. These classes control the overall appearance,
                                            as well as the showing and hiding via CSS transitions. You can modify any of
                                            this with custom CSS or overriding our default variables. It's also worth noting
                                            that just about any HTML can go within the <code>.accordion-body</code>, though
                                            the transition does limit overflow.

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end accordion -->
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Surat') }}</div>

                    <div class="card-body">
                        <form action="{{ route('drafter.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
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
                                        <input type="date" class="form-control" id="TanggalSurat"
                                            name="TanggalSurat">
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




                            <div class="form-group mb-3">
                                <label for="kepada">Penerima Surat</label>
                                <select class="form-control" data-trigger name="PenerimaSurat"
                                    id="choices-multiple-default" placeholder="This is a placeholder">
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
                                <select class="form-control" data-trigger name="BlindCarbonCopy[]"
                                    id="choices-multiple-bcc" placeholder="Pilih penerima BC" multiple>
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
                                <textarea class="form-control" id="ckeditor-classic" name="Isi" rows="10"
                                    placeholder="Masukkan isi surat"></textarea>
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
@push('js')
    <script>
        function changeKategori(data) {
            var id = data.value;
            $.ajax({
                type: "GET",
                url: "{{ route('kategori-surat.get-field', '') }}/" + id,
                data: {},
                dataType: "json",
                success: function(response) {
                    console.log(response);
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
