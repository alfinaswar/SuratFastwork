@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">

            {{-- <div class="pull-right">
            <a class="btn btn-primary" style="background-color:#6b6ef5;" href="{{ route('roles.index') }}"> Back</a>
        </div> --}}
        </div>
    </div>



    <div class="card">
        <div class="card-header border-bottom" style="background-color:white ">Edit New Role</div>
        <div class="card-body">
            <div class="preview-block">

                <div class="row gy-4">
                    <div class="col-sm-12">




                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> Ada yng salah<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">

                                    <strong>Name:</strong>
                                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <strong>Hak Akses:</strong>
                                    <div class="form-check">
                                        <div class="row row-cols-1 row-cols-md-4 g-3">
                                            @foreach ($permission->chunk(ceil($permission->count() / 4)) as $chunk)
                                                <div class="col">
                                                    @foreach ($chunk as $value)
                                                        <div class="mb-1">
                                                            <label
                                                                class="form-check-label">{{ Form::checkbox('permission[]', $value->name, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'form-check-input']) }}
                                                                {{ $value->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 ">
                                <br>
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
