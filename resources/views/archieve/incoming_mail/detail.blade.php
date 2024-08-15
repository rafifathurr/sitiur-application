@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Surat Masuk</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nomor</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->number }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Judul Surat</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($incoming_mail->date)) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Klasifikasi</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->classification->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Isi Surat</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $incoming_mail->typeMailContent->name }}
                        </div>
                    </div>
                    @if (is_null($incoming_mail->institution_id))
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Instansi</label>
                            <div class="col-sm-9 col-form-label">
                                Eksternal
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Instansi Polri Utama</label>
                            <div class="col-sm-9 col-form-label">
                                {{ !is_null($incoming_mail->institution->parent_id) ? $incoming_mail->institution->parent->name : $incoming_mail->institution->name }}
                            </div>
                        </div>
                        @if (!is_null($incoming_mail->institution->parent_id))
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $incoming_mail->institution->name }}
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $incoming_mail->description ?? '-' !!}
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>Lampiran</label>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($incoming_mail->attachment) as $attachment)
                                    <div class="col-sm-3 col-form-label">
                                        <a target="_blank" href="{{ asset($attachment) }}">Lampiran Surat Masuk<i
                                                class="fas fa-download ml-1"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                    @if (explode('.', $incoming_mail->attachment)[count(explode('.', $incoming_mail->attachment)) - 1] == 'pdf')
                        <div class="form-group row">
                            <label class="col-sm-12 col-form-label">Lampiran</label>
                            <div class="col-sm-12 col-form-label">
                                <iframe class="w-100 mt-3" style="height: 1040px;"
                                    src="{{ asset($incoming_mail->attachment) }}" width="1000" height="1000"
                                    frameborder="0"></iframe>
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lampiran</label>
                            <div class="col-sm-9 col-form-label">
                                @if (!is_null($incoming_mail->attachment))
                                    <a href="{{ asset($incoming_mail->attachment) }}" class="text-primary"
                                        target="_blank"><i class="fas fa-download mr-1"></i> Lampiran Surat Masuk</a>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="p-3 border border-1 rounded-5">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $incoming_mail->updatedBy->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                            <div class="col-sm-9 col-form-label">
                                {{ date('d F Y H:i:s', strtotime($incoming_mail->updated_at)) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.incoming-mail.index') }}" class="btn btn-sm btn-danger rounded-5">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
@endsection
