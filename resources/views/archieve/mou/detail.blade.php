@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Detail MOU</h4>
                </div>
                <div class="py-1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nomor MOU</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $mou->number_mou }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $mou->name }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('d F Y', strtotime($mou->date)) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Durasi Perjanjian</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $mou->duration . ' Tahun' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tahun Akhir Perjanjian</label>
                        <div class="col-sm-9 col-form-label">
                            {{ date('Y', strtotime($mou->date)) + $mou->duration }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tipe MOU</label>
                        <div class="col-sm-9 col-form-label">
                            {{ $mou->type == 0 ? 'Korlantas - Kemendikbud' : 'Kewilayahan - Disdik Wilayah' }}
                        </div>
                    </div>
                    @if ($mou->type == 0)
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
                                {{ !is_null($mou->institution->parent_id) ? $mou->institution->parent->name : $mou->institution->name }}
                            </div>
                        </div>
                        @if (!is_null($mou->institution->parent_id))
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Instansi Polri Wilayah</label>
                                <div class="col-sm-9 col-form-label">
                                    {{ $mou->institution->name }}
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9 col-form-label">
                            {!! $mou->description ?? '-' !!}
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>Lampiran</label>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($mou->attachment) as $attachment)
                                    <div class="col-sm-3 col-form-label">
                                        <a target="_blank" href="{{ asset($attachment) }}">Lampiran MOU<i
                                                class="fas fa-download ml-1"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                    @if (explode('.', $mou->attachment)[count(explode('.', $mou->attachment)) - 1] == 'pdf')
                        <div class="form-group row">
                            <label class="col-sm-12 col-form-label">Lampiran</label>
                            <div class="col-sm-12 col-form-label">
                                <iframe class="w-100 mt-3" style="height: 1040px;" src="{{ asset($mou->attachment) }}"
                                    width="1000" height="1000" frameborder="0"></iframe>
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lampiran</label>
                            <div class="col-sm-9 col-form-label">
                                <a href="{{ asset($mou->attachment) }}" class="text-primary" target="_blank"><i
                                        class="fas fa-download mr-1"></i> Lampiran MOU</a>
                            </div>
                        </div>
                    @endif
                    <div class="p-3 border border-1 rounded-5">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Oleh</label>
                            <div class="col-sm-9 col-form-label">
                                {{ $mou->updatedBy->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Diperbarui Pada</label>
                            <div class="col-sm-9 col-form-label">
                                {{ date('d F Y H:i:s', strtotime($mou->updated_at)) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.mou.index') }}" class="btn btn-sm btn-danger rounded-5">
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
