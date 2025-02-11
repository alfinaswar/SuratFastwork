@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
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
                            <a class="btn btn-primary" href="{{ route('master-penerima-ext.edit', $user->id) }}">Edit</a>
                            <button type="button" class="btn btn-danger"
                                onclick="deleteUser({{ $user->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/master-penerima-ext/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            alert('User deleted successfully.');
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert('Failed to delete user.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the user.');
                    });
            }
        }
    </script>
@endsection
