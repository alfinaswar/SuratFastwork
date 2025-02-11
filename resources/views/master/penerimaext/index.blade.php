@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'success',
                text: '{{ $message }}',
            });
        </script>
    @endif
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content mb-3">

                <div class="text-end">
                    <a href="{{ route('master-penerima-ext.create') }}" class="btn btn-primary btn-sm">Tambah Pengguna</a>
                </div>
            </div>
        </div>
        <div class="card card-bordered card-preview">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama</th>
                                <th>Inisial</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Perusahaan</th>
                                <th>Alamat</th>
                                <th>Surel</th>
                                <th>Website</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td class="text-center">{{ ++$i }}</td>
                                    <td>{{ $user->Nama }}</td>
                                    <td>{{ $user->Inisial }}</td>
                                    <td>{{ $user->Jabatan }}</td>
                                    <td>{{ $user->Departemen }}</td>
                                    <td>{{ $user->Perusahaan }}</td>
                                    <td>{{ $user->Alamat }}</td>
                                    <td>{{ $user->Surel }}</td>
                                    <td>{{ $user->Website }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-primary"
                                            href="{{ route('master-penerima-ext.edit', $user->id) }}">Edit</a>
                                        <button type="button" class="btn btn-danger"
                                            onclick="deleteUser({{ $user->id }})">Delete</button>

                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('master-penerima-ext.destroy', $user->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endsection

            @push('js')
                <script>
                    function deleteUser(userId) {
                        Swal.fire({
                            title: "Apakah Anda yakin?",
                            text: "Data ini akan dihapus secara permanen!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Ya, Hapus!",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('delete-form-' + userId).submit();
                            }
                        });
                    }

                    // Notifikasi sukses setelah penghapusan
                    @if (session('success'))
                        Swal.fire({
                            title: "Berhasil!",
                            text: "{{ session('success') }}",
                            icon: "success",
                            confirmButtonText: "OK"
                        });
                    @endif
                </script>
            @endpush
