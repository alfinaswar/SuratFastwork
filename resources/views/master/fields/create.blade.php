@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Surat') }}</div>

                    <div class="card-body">
                        <form action="{{ route('master-field.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="JudulField">Judul Field</label>
                                        <input type="text" class="form-control" id="JudulField" name="JudulField"
                                            placeholder="Judul Field">
                                        @error('JudulField')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="Tipe">Tipe</label>
                                        <select class="form-control" name="Tipe" id="Tipe">
                                            <option value="text">Text</option>
                                            <option value="number">Checklist</option>
                                            <option value="date">Date</option>
                                        </select>
                                        @error('Tipe')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Sintaks">Sintaks</label>
                                <textarea class="form-control" id="Sintaks" name="Sintaks" rows="3" placeholder="Sintaks"></textarea>
                                @error('Sintaks')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="card-footer mt-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('master-field.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
