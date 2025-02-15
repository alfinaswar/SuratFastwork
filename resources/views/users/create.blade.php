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
        'route' => 'users.store',
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
                            <label class="form-label">Name</label>
                            {!! Form::text('name', null, ['placeholder' => 'Nama', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Inisial Nama</label>
                            {!! Form::text('inisial', null, ['placeholder' => 'Inisial Nama', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Inisial</label>
                            {!! Form::text('inisial', null, ['placeholder' => 'Inisial Nama', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Email</label>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Nomor Identitas Pegawai</label>
                            {!! Form::text('nip', null, ['placeholder' => 'Nomor Identitas Pegawai', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Perusahaan</label>
                            {!! Form::text('perusahaan', null, ['placeholder' => 'Nama Perusahaan', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Role</label>
                            {!! Form::select('roles[]', $roles, [], ['class' => 'me-sm-2 default-select form-control wide']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Departmen</label>
                            {!! Form::select('department', $departmen->pluck('NamaDepartemen', 'id'), null, [
        'class' => 'me-sm-2 default-select form-control wide',
    ]) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Jabatan</label>
                            {!! Form::text('jabatan', null, ['placeholder' => 'Jabatan', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Nomor HP</label>
                            {!! Form::number('hp', null, ['placeholder' => 'Nomor HP', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Alamat</label>
                            {!! Form::textarea('alamat', null, ['placeholder' => 'Alamat', 'class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Website</label>
                            {!! Form::text('website', null, ['placeholder' => 'Link Website', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Password</label>
                            {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Confirm Password</label>
                            {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Foto</label>
                            <input type="file" name="Foto" class="form-control" id="profileImageInput" accept="image/*">
                        </div>
                        <div class="col-sm-6 m-b30">
                            <label class="form-label">Tanda Tangan</label>
                            <input type="file" name="DigitalSign" class="form-control" accept="image/*">
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
        document.getElementById('profileImageInput').addEventListener('change', function (event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
