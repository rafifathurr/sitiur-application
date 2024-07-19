@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Tambah Galeri</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('archieve.gallery.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nama" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ old('date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="attachment">Lampiran Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="videoInput" name="attachment" accept="image/*"
                            required>
                        <p class="text-danger py-1">* .png .jpg .jpeg</p>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{{ old('description') }}</textarea>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.documentation.index') }}" class="btn btn-sm btn-danger rounded-5">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary rounded-5 mx-2">
                            Simpan
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </form>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.archieve.gallery.script')
    @endpush
@endsection
