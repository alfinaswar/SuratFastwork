@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Approval Surat') }}</div>
                    <div class="card-body">
                        <style>
                            .header {
                                text-align: center;
                                margin-bottom: 30px;
                                text-transform: uppercase;
                                font-weight: bold;
                            }

                            .letter-info {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 20px;
                            }

                            .letter-number {
                                float: left;
                            }

                            .letter-date {
                                float: right;
                            }

                            .attachments {
                                margin-bottom: 20px;
                            }

                            .recipient {
                                margin-bottom: 30px;
                            }

                            .subject {
                                margin-bottom: 20px;
                            }

                            .content {
                                margin-bottom: 30px;
                                min-height: 200px;
                            }

                            .signature {
                                margin-top: 50px;
                            }

                            .copies {
                                margin-top: 30px;
                            }

                            ul {
                                list-style-type: none;
                                padding-left: 0;
                            }

                            .bold {
                                font-weight: bold;
                            }
                        </style>

                        <div class="card">
                            <div class="card-body">
                                <div class="header">
                                    {{ $surat->kop_perusahaan }}
                                </div>

                                <div class="letter-info">
                                    <div class="letter-number">
                                        No. Surat: {{ $surat->NomorSurat }}<br>
                                        Lampiran:<br>
                                        <ul>

                                            <li>- {{ $surat->Lampiran }}</li>

                                        </ul>
                                    </div>
                                    <div class="letter-date">
                                        {{ \Carbon\Carbon::parse($surat->TanggalSurat)->format('d F Y') }}</div>
                                </div>

                                <div class="recipient">
                                    Kepada Yth.<br>
                                    <span class="bold">{{ $surat->getPenerima->name }}</span><br>
                                    <span class="bold">{{ $surat->getPenerima->perusahaan }}</span><br>
                                    <span class="bold">{{ $surat->getPenerima->jabatan }}</span><br>
                                    Di {{ $surat->getPenerima->alamat }}
                                </div>

                                <div class="subject">
                                    <span class="bold">Perihal: {{ $surat->Perihal }}</span>
                                </div>

                                <div>Dengan hormat,</div>

                                <div class="content">
                                    {!! $surat->Isi !!}
                                </div>

                                <div>Demikian permohonan ini kami sampaikan. Atas perhatiannya kami ucapkan terima kasih.
                                </div>

                                <div class="signature">
                                    Hormat kami,<br>
                                    {{ $surat->getPenulis->name }}<br><br><br><br>
                                    {{ $surat->getPenulis->jabatan }}
                                </div>

                                <div class="copies">
                                    Tembusan Yth:<br>
                                    {{-- @foreach (json_decode($surat->Tembusan) as $tembusan)
                                        - {{ $tembusan }}<br>
                                    @endforeach --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
                                    <option value="Verified" {{ $surat->status == 'Verified' ? 'selected' : '' }}>
                                        Verified
                                    </option>
                                    <option value="Revision" {{ $surat->status == 'Revision' ? 'selected' : '' }}>
                                        Revision
                                    </option>
                                </select>
                            </div>


                            <div class="form-group mb-3">
                                <label for="revisi">Catatan Revisi</label>
                                <textarea name="Catatan" class="form-control" rows="4">{{ old('revisi', $surat->revisi) }}</textarea>
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
