@extends('layouts.app')

@section('content')
    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
    <div class="container"
        style="width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20px; border: 1px solid #000; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <header style="text-align: center; margin-bottom: 20px;">

            <h2>{{ $surat->KodeProject }} - {{ $surat->NomorSurat }}</h2>
            <p>{{ date('d F Y', strtotime($surat->TanggalSurat)) }}</p>
        </header>

        <section>
            <div class="row">
                <div class="col-md-6">
                    <section>
                        <p><strong>Kepada Yth:</strong></p>
                        <p>{{ $surat->getPenerima->name }}</p>
                    </section>
                </div>
                <div class="col-md-6">
                    <section>
                        <p><strong>Kepada Yth (Eksternal):</strong></p>
                        <p>{{ $surat->getPenerimaEks->Nama ?? '' }}</p>
                    </section>
                </div>
                @if ($surat->CarbonCopy != null)
                    <div class="col-md-6">
                        <section>
                            <p><strong>CC:</strong></p>
                            @foreach ($surat->CC as $cc)
                                <p>{{ $cc->name }} - {{ $cc->perusahaan ?? 'tidak di isi' }}</p>
                            @endforeach
                        </section>
                    </div>
                @endif
                @if ($surat->BlindCarbonCopy != null)
                    <div class="col-md-6">
                        <section>
                            <p><strong>BCC:</strong></p>
                            @foreach ($surat->BlindCC as $bcc)
                                <p>{{ $bcc->name }} - {{ $bcc->perusahaan ?? 'tidak di isi' }}</p>
                            @endforeach
                        </section>
                    </div>

                @endif
            </div>
        </section>

        <section style="margin-top: 20px;">
            <p><strong>Perihal:</strong> {{ $surat->Perihal }}</p>
        </section>

        <section style="margin-top: 20px;">
            <p>{!! $surat->Isi !!}</p>
        </section>

        <section style="margin-top: 40px;">
            <p><strong>Pengirim:</strong></p>
            <p>{{ $surat->NamaPengirim->name ?? 'Belum Dikirim' }}</p>
        </section>

        {{-- @if($CarbonCopy || $CarbonCopyEks)
        <section style="margin-top: 20px;">
            <p><strong>Tembusan:</strong></p>
            <p>{{ $CarbonCopy }}</p>
            @if($CarbonCopyEks)
            <p>{{ $CarbonCopyEks }}</p>
            @endif
        </section>
        @endif

        @if($BlindCarbonCopy || $BlindCarbonCopyEks)
        <section style="margin-top: 20px;">
            <p><strong>Tembusan Tertutup:</strong></p>
            <p>{{ $BlindCarbonCopy }}</p>
            @if($BlindCarbonCopyEks)
            <p>{{ $BlindCarbonCopyEks }}</p>
            @endif
        </section>
        @endif --}}

        <footer style="margin-top: 50px;">
            <p><strong>Dibuat Oleh:</strong> {{ $surat->getPenulis->name }}</p>
            <p><strong>Status:</strong> {{ $surat->Status }}</p>
        </footer>
    </div>
@endsection