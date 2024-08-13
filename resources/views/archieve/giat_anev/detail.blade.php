@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail Giat Anev Diseminasi</h4>
                </div>
                <div class="py-1">
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nomor</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $giat_anev->number_giat }}
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $giat_anev->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($giat_anev->date)) }}
                        </div>
                    </div>
                    @if (is_null($giat_anev->institution_id))
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
                                {{ !is_null($giat_anev->institution->parent_id) ? $giat_anev->institution->parent->name : $giat_anev->institution->name }}
                            </div>
                        </div>
                        @if (!is_null($giat_anev->institution->parent_id))
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $giat_anev->institution->name }}
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $giat_anev->description ?? '-' !!}
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>Lampiran</label>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($giat_anev->attachment) as $attachment)
                                    <div class="col-sm-3 col-form-label">
                                        <a target="_blank" href="{{ asset($attachment) }}">Lampiran Giat Anev<i
                                                class="fas fa-download ml-1"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                    @if (explode('.', $giat_anev->attachment)[count(explode('.', $giat_anev->attachment)) - 1] == 'pdf')
                        <div class="form-group row">
                            <label class="col-sm-12 col-form-label">Lampiran</label>
                            <div class="col-sm-12 col-form-label">
                                <iframe class="w-100 mt-3" style="height: 1040px;" src="{{ asset($giat_anev->attachment) }}"
                                    width="1000" height="1000" frameborder="0"></iframe>
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lampiran</label>
                            <div class="col-sm-9 col-form-label">
                                <a href="{{ asset($giat_anev->attachment) }}" class="text-primary" target="_blank"><i
                                        class="fas fa-download mr-1"></i> Lampiran Giat Anev Diseminasi</a>
                            </div>
                        </div>
                    @endif
                    <div class="p-3 border border-1 rounded-5">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $giat_anev->updatedBy->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                            <div class="col-sm-9 col-form-label">
                                {{ date('d F Y H:i:s', strtotime($giat_anev->updated_at)) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.giat-anev.index') }}" class="btn btn-sm btn-danger rounded-5">
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
