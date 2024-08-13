@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Dokumentasi Video</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $documentation->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($documentation->date)) }}
                        </div>
                    </div>
                    @if (is_null($documentation->institution_id))
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
                                {{ !is_null($documentation->institution->parent_id) ? $documentation->institution->parent->name : $documentation->institution->name }}
                            </div>
                        </div>
                        @if (!is_null($documentation->institution->parent_id))
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $documentation->institution->name }}
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $documentation->description ?? '-' !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lampiran Video</label>
                        <div class="col-sm-12 rounded-5 border border-1-grey mt-3">
                            <video id="videoPreview" class="w-100 mt-3" controls>
                                <source src="{{ asset($documentation->attachment) }}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <div class="p-3 border border-1 rounded-5">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $documentation->updatedBy->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                            <div class="col-sm-9 col-form-label">
                                {{ date('d F Y H:i:s', strtotime($documentation->updated_at)) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.documentation.index') }}" class="btn btn-sm btn-danger rounded-5">
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
