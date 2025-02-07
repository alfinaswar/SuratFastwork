@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <!-- card -->
                <div class="card card-h-100">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center mb-4">
                            <h5 class="card-title me-2">Detail Draft Surat</h5>
                            <div class="ms-auto">
                                <a href="{{ route('verifikator.download-preview', $surat->id) }}"
                                    class="btn btn-primary btn-sm">Unduh
                                    Draft Surat</a>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm-3 text-center">
                                <img src="{{ asset('assets/images/icon/word.png') }}">
                            </div>
                            <div class="col-sm align-self-center">
                                <div class="mt-4 mt-sm-0">
                                    <p class="mb-1 fw-bold">Nomor Dokumen</p>
                                    <h4>{{ $surat->NomorSurat }}</h4>
                                    <div class="row g-0">
                                        <div class="col-2">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Drafter</p>
                                                <h5 class="fw-medium">{{ $surat->getPenulis->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Penerima Surat</p>
                                                <h5 class="fw-medium">{{ $surat->getPenerima->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Carbon Copy</p>
                                                <h5 class="fw-medium">
                                                    @foreach ($surat->CC as $penerima)
                                                        <span class="badge bg-primary">{{ $penerima->name }}</span>,
                                                    @endforeach
                                                </h5>
                                            </div>
                                        </div>
                                        {{-- <div class="col-4">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Blind Carbon Copy</p>
                                                <h5 class="fw-medium">
                                                    @foreach ($surat->CarbonCC as $carbon)
                                                        <span class="badge bg-primary">{{ $carbon->name }}</span>,
                                                    @endforeach
                                                </h5>
                                            </div>
                                        </div> --}}

                                    </div>
                                    <div class="mt-2">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <h5 class="card-header bg-transparent border-bottom text-uppercase">Isi
                                                    Surat
                                                </h5>
                                                <div class="card-body">
                                                    <h4 class="card-title">Perihal : {{ $surat->Perihal }}</h4>
                                                    <p class="card-text">{!! $surat->Isi !!}</p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div>
                                            <label class="mb-2 text-muted text-uppercase font-size-11">Lampiran</label>
                                            <div class="fw-medium">
                                                @foreach ($surat->FileLampiran as $lampiran)
                                                    <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show"
                                                        role="alert">
                                                        <i class="mdi mdi-file-document label-icon"></i><strong><a
                                                                href="{{ asset('storage/lampiran/' . $lampiran) }}">
                                                                {{ $lampiran }}</strong></a>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row text-end">
                                <div class="col-md-12">
                                    <div class="">
                                        <a href="javascript:void(0);"
                                            onclick="confirmRead('{{ route('surat-masuk.read', $surat->id) }}')"
                                            class="btn btn-success">
                                            <i class="mdi mdi-check-circle"></i> Tandai Telah Dibaca
                                        </a>

                                        {{-- <a href="javascript:void(0);"
                                            onclick="confirmDelete('{{ route('surat-masuk.delete', $surat->id) }}')"
                                            class="btn btn-danger">
                                            <i class="mdi mdi-delete"></i> Hapus Pesan
                                        </a> --}}


                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    @endsection
    @push('js')
        <script>
            function confirmRead(readUrl) {
                Swal.fire({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menandai surat ini telah dibaca?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Tandai!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = readUrl;
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Surat telah ditandai sebagai telah dibaca",
                            icon: "success",
                            timer: 1500
                        });
                    }
                });
            }

            function confirmDelete(deleteUrl) {
                Swal.fire({
                    title: "Konfirmasi Hapus",
                    text: "Apakah Anda yakin ingin menghapus surat ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Surat telah berhasil dihapus",
                            icon: "success",
                            timer: 1500
                        });
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
