@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('master-departemen.create') }}" class="btn btn-primary">Tambah Departemen</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Kode</th>
                <th>Nama Departemen</th>
                <th width="15%" class="justify-content-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departemens as $key => $departemen)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $departemen->Kode }}</td>
                    <td>{{ $departemen->NamaDepartemen }}</td>
                    <td class="text-center">
                        <a href="{{ route('master-departemen.edit', $departemen->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                            data-id="{{ $departemen->id }}">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (session()->has('success'))
        <script>
            setTimeout(function () {
                swal.fire({
                    title: "{{ __('Sukses!') }}",
                    text: "{!! \Session::get('success') !!}",
                    icon: "sukses"
                });
            }, 1000);
        </script>
    @endif
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('body').on('click', '.btn-delete', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Hapus Data',
                text: "Anda Ingin Menghapus Data?",
                icon: 'peringatan',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('master-departemen.destroy', ':id') }}'.replace(
                            ':id',
                            id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire(
                                'Dihapus',
                                'Data Berhasil Dihapus',
                                'sukses'
                            );

                            $('#datatable').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
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

    });
</script>
