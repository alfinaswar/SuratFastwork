@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap">
        {{-- <a href="{{ route('drafter.create') }}" class="btn btn-primary me-3 btn-sm"><i class="fas fa-plus me-2"></i>Tambah</a> --}}
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Verifikasi Surat</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nomor</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <script>
            setTimeout(function() {
                swal.fire({
                    title: "{{ __('Success!') }}",
                    text: "{!! \Session::get('success') !!}",
                    icon: "success"
                });
            }, 1000);
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            setTimeout(function() {
                swal.fire({
                    title: "{{ __('Error!') }}",
                    text: "{!! \Session::get('error') !!}",
                    icon: "error"
                });
            }, 1000);
        </script>
    @endif

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
                    ajax: "{{ route('surat-terkirim.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'NomorSurat',
                            name: 'NomorSurat'
                        },
                        {
                            data: 'Perihal',
                            name: 'Perihal'
                        },
                        {
                            data: 'StatusLabel',
                            name: 'StatusLabel'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            };
            dataTable();
            $(".multi-select").select2();
        });
    </script>
@endsection
