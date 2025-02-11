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

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {!! Form::open([
        'route' => 'master-penerima-ext.store',
        'method' => 'POST',
        'class' => 'profile-form',
        'enctype' => 'multipart/form-data',
    ]) !!}
    <div class="row">
        <div class="col-xl-12 col-lg-8">
            <div class="card card-bx m-b30">
                <div class="card-header">
                    <h6 class="title">Data Karyawan</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Nama</label>
                            {!! Form::text('Nama', null, ['placeholder' => 'Nama', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Inisial</label>
                            {!! Form::text('Inisial', null, ['placeholder' => 'Inisial', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Jabatan</label>
                            {!! Form::text('Jabatan', null, ['placeholder' => 'Jabatan', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Departemen</label>
                            {!! Form::text('Departemen', null, ['placeholder' => 'Departemen', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Perusahaan</label>
                            {!! Form::text('Perusahaan', null, ['placeholder' => 'Perusahaan', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Alamat</label>
                            {!! Form::textarea('Alamat', null, ['placeholder' => 'Alamat', 'class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Surat Elektronik</label>
                            {!! Form::text('Surel', null, ['placeholder' => 'Surat Elektronik', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Website</label>
                            {!! Form::text('Website', null, ['placeholder' => 'Website', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
    <script>
        document.getElementById('profileImageInput').addEventListener('change', function(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
