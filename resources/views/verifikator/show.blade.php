@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <!-- card -->
                <div class="card card-h-100">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center mb-4">
                            <h5 class="card-title me-2">Detail Draft Surat</h5>
                            <div class="ms-auto">
                                <a href="{{ route('verifikator.download-preview', $surat->id) }}"
                                    class="btn btn-primary btn-sm">Unduh
                                    Draft Surat</a>
                                <a href="{{ route('verifikator.show-surat', $surat->id) }}"
                                    class="btn btn-secondary btn-sm">Tampilkan
                                    Surat</a>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm text-center">
                                <img src="{{ asset('assets/images/icon/word.png') }}">
                            </div>
                            <div class="col-sm align-self-center">
                                <div class="mt-4 mt-sm-0">
                                    <p class="mb-1 fw-bold">Nomor Dokumen</p>
                                    <h4>{{ $surat->NomorSurat }}</h4>
                                    <div class="row g-0">
                                        <div class="col-6">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Drafter</p>
                                                <h5 class="fw-medium">{{ $surat->getPenulis->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div>
                                                <p class="mb-2 text-muted text-uppercase font-size-11">Penerima Surat</p>
                                                <h5 class="fw-medium">{{ $surat->getPenerima->name }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="mt-2">
                                        <a href="#" class="btn btn-primary btn-sm">View more <i
                                                class="mdi mdi-arrow-right ms-1"></i></a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Update Status dan Catatan Revisi
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('verifikator.store') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="Status" class="form-control" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="Verified" {{ $surat->Status == 'Verified' ? 'selected' : '' }}>
                                        Verified
                                    </option>
                                    <option value="Revision" {{ $surat->Status == 'Revision' ? 'selected' : '' }}>
                                        Revision
                                    </option>
                                </select>
                            </div>


                            <div class="form-group mb-3">
                                <label for="revisi">Catatan Revisi</label>
                                <textarea name="Catatan" class="form-control" id="ckeditor-classic" name="Isi" rows="10">{{ old('revisi', $surat->getCatatan->Catatan ?? '') }}</textarea>
                            </div>
                            <input type="hidden" name="idsurat" value="{{ old('idsurat', $surat->id) }}">

                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
