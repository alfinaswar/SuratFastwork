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
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">Tambah Role / Hak Akses</a>
                </div>
            </div>
        </div>

        <div class="card card-bordered card-preview">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="display" style="min-width: 845px" width="100%">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th>Name</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>

                                        <a class="btn btn-primary btn-md" href="{{ route('roles.edit', $role->id) }}"><i
                                                class="fa fa-edit"></i></a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
@endsection
