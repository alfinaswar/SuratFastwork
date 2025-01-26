@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Template</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Template</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $template->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ $template->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">File Template (Word)</label>
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank"
                                    class="btn btn-link">
                                    <i class="fas fa-download me-2"></i>Lihat File Saat Ini
                                </a>
                            </div>
                            <input type="file" name="file" id="file" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <script>
            setTimeout(function() {
                swal.fire({
                    title: "{{ __('Success!') }}",
                    text: "{{ session('success') }}",
                    icon: "success"
                });
            }, 500);
        </script>
    @endif
@endsection
