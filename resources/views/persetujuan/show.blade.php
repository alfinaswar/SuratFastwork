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
                                <a href="{{ route('persetujuan.show-surat', $surat->id) }}"
                                    class="btn btn-secondary btn-sm">Tampilkan
                                    Surat</a>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm-3 text-center">
                                <img src="{{ asset('assets/images/icon/word.png') }}" width="auto">
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
                                        @if($surat->CarbonCC != null)
                                            <div class="col-4">
                                                <div>
                                                    <p class="mb-2 text-muted text-uppercase font-size-11">Blind Carbon Copy</p>
                                                    <h5 class="fw-medium">
                                                        @foreach ($surat->CarbonCC as $carbon)
                                                            <span class="badge bg-primary">{{ $carbon->name }}</span>,
                                                        @endforeach
                                                    </h5>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="col-12 mt-3">
                                        <div>
                                            <label class="mb-2 text-muted text-uppercase font-size-11">Lampiran</label>
                                            <div class="fw-medium">
                                                @if(count($surat->FileLampiran) < 1)
                                                    <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show"
                                                        role="alert">
                                                        <i class="mdi mdi-information label-icon"></i><strong>Tidak ada
                                                            lampiran.</strong>
                                                    </div>
                                                @else
                                                    @foreach ($surat->FileLampiran as $lampiran)
                                                        <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show"
                                                            role="alert">
                                                            <i class="mdi mdi-file-document label-icon"></i><strong><a
                                                                    href="{{ asset('storage/lampiran/' . $lampiran) }}">
                                                                    {{ $lampiran }}</strong></a>

                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-2">
                                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show"
                                        role="alert">
                                        <i class="mdi mdi-check-all label-icon"></i><strong>Catatan</strong> - Catatan
                                        Verfikator
                                    </div>
                                    {!! $surat->getCatatan->Catatan ?? 'Tidak ada catatan' !!}
                                </div>
                            </div>
                        </div>
                        <div class="row text-end">
                            <div class="col-md-12">
                                <div class="">
                                    <a href="javascript:void(0);"
                                        onclick="confirmApproval('{{ route('persetujuan.approve', $surat->id) }}')"
                                        class="btn btn-success">
                                        <i class="mdi mdi-check-circle"></i> Setujui dan Kirim ke Penerima
                                    </a>

                                    <a href="{{ route('persetujuan.reject', $surat->id) }}" class="btn btn-danger"><i
                                            class="mdi mdi-close-circle"></i> Tolak</a>
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
        function confirmApproval(approveUrl) {
            Swal.fire({
                title: "Konfirmasi Persetujuan",
                text: "Apakah Anda yakin ingin menyetujui dan mengirim surat ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Setujui!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = approveUrl;
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

                reader.onload = function (e) {
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