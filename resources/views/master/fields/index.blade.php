@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap">
        <a href="{{ route('master-field.create') }}" class="btn btn-primary me-3 btn-sm"><i
                class="fas fa-plus me-2"></i>Tambah</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Surat</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Field</th>
                                    <th>Tipe</th>
                                    <th>Sintaks</th>
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
    @push('js')
        <script>
            $(document).ready(function() {
                $('body').on('click', '.btn-delete', function() {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: 'Hapus Data',
                        text: "Anda Ingin Menghapus Data?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('master-field.destroy', ':id') }}'.replace(':id',
                                    id),
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Dihapus',
                                        'Data Berhasil Dihapus',
                                        'success'
                                    );

                                    $('#datatable').DataTable().ajax.reload();
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Gagal!',
                                        xhr.responseJSON.message || 'Gagal',
                                        'error'
                                    );
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
                });

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
                        ajax: "{{ route('master-field.index') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'JudulField',
                                name: 'JudulField'
                            },
                            {
                                data: 'Tipe',
                                name: 'Tipe'
                            },
                            {
                                data: 'Sintaks',
                                name: 'Sintaks'
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
    @endpush
@endsection
