<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>E - SURAT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}assets/images/favicon.ico">
    <!-- plugin css -->
    <link href="{{ asset('') }}assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css"
        rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('') }}assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />
    <!-- Choices -->
    <link href="{{ asset('') }}assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet"
        type="text/css" />

    {{-- <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/preloader.min.css" type="text/css" /> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('') }}assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('') }}assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <!-- card -->
                <div class="card card-h-100">
                    <!-- card body -->
                    <div class="card-body">
                        <!-- Section 1: Identitas Surat -->
                        <div class="row align-items-center mb-4">

                            <div class="col-sm align-self-center">
                                <div class="mt-4 mt-sm-0">
                                    <p class="mb-1 fw-bold text-center">Nomor Dokumen</p>
                                    <h4 class="text-center text-decoration-underline">{{ $surat->NomorSurat }}</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Drafter</th>
                                                    <td>{{ $surat->getPenulis->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Penerima Surat</th>
                                                    <td>{{ $surat->getPenerima->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Carbon Copy</th>
                                                    <td>
                                                        @foreach ($surat->CC as $penerima)
                                                            <span
                                                                class="badge bg-primary">{{ $penerima->name }}</span>,
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Blind Carbon Copy</th>
                                                    <td>
                                                        @foreach ($surat->CarbonCC as $carbon)
                                                            <span class="badge bg-primary">{{ $carbon->name }}</span>,
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Carbon Copy Eksternal</th>
                                                    <td>
                                                        @foreach ($surat->CCEks as $penerima)
                                                            <span
                                                                class="badge bg-primary">{{ $penerima->name }}</span>,
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Blind Carbon Copy Eksternal</th>
                                                    <td>
                                                        @foreach ($surat->CarbonCCEks as $carbon)
                                                            <span class="badge bg-primary">{{ $carbon->name }}</span>,
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Lampiran -->
                        <div class="row">

                            <div class="col-12 mt-3">
                                <div class="col-sm-3 text-center">
                                    <div class="mb-3">
                                        <strong>Verifikator</strong>
                                        <img src="{{ asset('storage/DigitalSign/' . $surat->VerifiedBy->DigitalSign) }}"
                                            alt="Verifikator Signature" class="img-fluid">
                                        <p class="fw-bold text-decoration-underline">
                                            {{ $surat->VerifiedBy->name ?? 'Tidak ada' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Persetujuan</strong>
                                        <img src="{{ asset('storage/DigitalSign/' . $surat->ApprovedBy->DigitalSign) }}"
                                            alt="Persetujuan Signature" class="img-fluid">
                                        <p class="fw-bold text-decoration-underline">
                                            {{ $surat->ApprovedBy->name ?? 'Tidak ada' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Pengirim</strong>
                                        <img src="{{ asset('storage/DigitalSign/' . $surat->SentBy->DigitalSign) }}"
                                            alt="Pengiriman Signature" class="img-fluid">
                                        <p class="fw-bold text-decoration-underline">
                                            {{ $surat->SentBy->name ?? 'Tidak ada' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Pembuat</strong>
                                        <img src="{{ asset('storage/DigitalSign/' . $surat->DibuatOleh->DigitalSign) }}"
                                            alt="Pembuat Signature" class="img-fluid">
                                        <p class="fw-bold text-decoration-underline">
                                            {{ $surat->DibuatOleh->name ?? 'Tidak ada' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2 text-muted text-uppercase font-size-11">Lampiran</label>
                                    <div class="fw-medium">
                                        @if (count($surat->FileLampiran) < 1)
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

                        <!-- Section 3: Kolom Tanda Tangan -->
                        <div class="row text-center">

                            <div class="col-md-12">
                                <div class="">
                                    <a href="{{ route('surat-terkirim.download', $surat->id) }}"
                                        class="btn btn-primary">
                                        <i class="mdi mdi-file-document"></i> Unduh Dokumen dan Lampiran
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('') }}assets/libs/jquery/jquery.min.js"></script> --}}
    <script src="{{ asset('') }}assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ asset('') }}assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('') }}assets/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('') }}assets/libs/feather-icons/feather.min.js"></script>
    <!-- pace js -->
    <script src="{{ asset('') }}assets/libs/pace-js/pace.min.js"></script>

    <!-- apexcharts -->
    <script src="{{ asset('') }}assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Plugins js-->
    <script src="{{ asset('') }}assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="{{ asset('') }}assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js">
    </script>
    <!-- dashboard init -->
    <script src="{{ asset('') }}assets/js/pages/dashboard.init.js"></script>

    <script src="{{ asset('') }}assets/js/app.js"></script>
    <!-- Required datatable js -->
    <script src="{{ asset('') }}assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- choices js -->
    <script src="{{ asset('') }}assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('') }}assets/js/pages/datatables.init.js"></script>
    <script src="{{ asset('') }}assets/js/pages/form-advanced.init.js"></script>
    <!-- ckeditor -->
    <script src="{{ asset('') }}assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

    <!-- init js -->
    <script src="{{ asset('') }}assets/js/pages/form-editor.init.js"></script>
    <script>
        feather.replace();
    </script>
    @stack('js')
</body>

</html>
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
