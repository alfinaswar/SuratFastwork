@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Draft Surat</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $countDraft }}">{{ $countDraft }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="ms-1 text-muted font-size-13">Dari Total {{ $countTotal }}</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Surat Diverifikasi</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $countVerified }}">{{ $countVerified }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="ms-1 text-muted font-size-13">Dari Total {{ $countTotal }}</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Surat Terkirim</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $countSent }}">{{ $countSent }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="ms-1 text-muted font-size-13">Dari Total {{ $countTotal }}</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Surat</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $countTotal }}">{{ $countTotal }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="ms-1 text-muted font-size-13">Dari Total {{ $countTotal }}</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Log Aktifitas User</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Pada</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var dataTable = function() {
                var table = $('#datatable');
                table.DataTable({
                    responsive: true,
                    serverSide: true,
                    bDestroy: true,
                    processing: true,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span> ',
                        paginate: {
                            next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                            previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                        }
                    },
                    ajax: "{{ route('log.getLog') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'get_user.name',
                            name: 'get_user.name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'pada',
                            name: 'pada'
                        },
                        {
                            data: 'waktu',
                            name: 'waktu'
                        },

                    ]
                });
            };
            dataTable();
        });
    </script>
@endpush
