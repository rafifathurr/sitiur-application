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
                        <label class="col-sm-3 col-form-label">Nama</label>
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
                                Kemendikbud
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Instansi Polri Utama</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $incoming_mail->institution->parent->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $incoming_mail->institution->name }}
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $incoming_mail->description ?? '-' !!}
                        </div>
                    </div>
                    <div class="form-group">
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
                    </div>
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