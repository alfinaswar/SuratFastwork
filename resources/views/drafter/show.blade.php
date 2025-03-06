@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm p-4" style="max-width: 900px; margin: auto;">
            <header class="text-center mb-4">
                <h2 class="h4">{{ $surat->KodeProject }} - {{ $surat->NomorSurat }}</h2>
                <p>{{ date('d F Y', strtotime($surat->TanggalSurat)) }}</p>
            </header>

            <section class="row g-4">
                <div class="col-12 col-md-6">
                    <p><strong>Kepada Yth:</strong></p>
                    <p>{{ $surat->getPenerima->name }}</p>
                </div>

                <div class="col-12 col-md-6">
                    <p><strong>Kepada Yth (Eksternal):</strong></p>
                    <p>{{ $surat->getPenerimaEks->Nama ?? '-' }}</p>
                </div>

                @if ($surat->CarbonCopy != null)
                    <div class="col-12 col-md-6">
                        <p><strong>CC:</strong></p>
                        @foreach ($surat->CC as $cc)
                            <p>{{ $cc->name }} - {{ $cc->perusahaan ?? 'Tidak diisi' }}</p>
                        @endforeach
                    </div>
                @endif

                @if ($surat->BlindCarbonCopy != null)
                    <div class="col-12 col-md-6">
                        <p><strong>BCC:</strong></p>
                        @foreach ($surat->BlindCC as $bcc)
                            <p>{{ $bcc->name }} - {{ $bcc->perusahaan ?? 'Tidak diisi' }}</p>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="mt-4">
                <p><strong>Perihal:</strong> {{ $surat->Perihal }}</p>
            </section>

            <section class="mt-4">
                <p>{!! nl2br(e($surat->Isi)) !!}</p>
            </section>

            <section class="mt-5">
                <p><strong>Pengirim:</strong></p>
                <p>{{ $surat->NamaPengirim->name ?? 'Belum Dikirim' }}</p>
            </section>

            <footer class="mt-5">
                <p><strong>Dibuat Oleh:</strong> {{ $surat->getPenulis->name }}</p>
                <p><strong>Status:</strong> {{ $surat->Status }}</p>
            </footer>
        </div>
    </div>
@endsection
