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
                <th>#</th>
                <th>Kode</th>
                <th>Nama Departemen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departemens as $key => $departemen)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $departemen->Kode }}</td>
                    <td>{{ $departemen->NamaDepartemen }}</td>
                    <td>
                        <a href="{{ route('departemen.edit', $departemen->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('departemen.destroy', $departemen->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus departemen ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
@endsection
