@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5 pt-lg-5 pt-md-3">
        <div class="mx-lg-5 px-5">
            <div class="card border border-0">
                <div class="card-body p-0">
                    <div class="az-dashboard-one-title">
                        <h4 class="az-dashboard-title" id="title">Daftar Giat Kampung Tertib</h4>
                        <div class="my-auto text-right">
                            <select class="form-control my-3" id="year" name="year" onchange="dataTable()">
                                <option disabled hidden selected>Pilih Tahun</option>
                                @foreach ($years as $index => $year)
                                    <option value="{{ $year['year'] }}" @if ($index == 0) selected @endif>
                                        {{ $year['year'] }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('archieve.giat-kampung-tertib.create') }}"
                                class="btn btn-sm rounded-5 btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Giat Anev
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <input type="hidden" id="datatable-url" value="{{ $dt_route }}">
                        <table class="table table-bordered mg-b-0" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Instansi</th>
                                    <th>Judul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- table-responsive -->
                    <!-- az-dashboard-one-title -->
                </div>
                <!-- az-content-body -->
            </div>
        </div>
    </div>
    @push('js-bottom')
        @include('js.archieve.giat_kampung_tertib.script')
        <script>
            dataTable();
        </script>
    @endpush
@endsection
