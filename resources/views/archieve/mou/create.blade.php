@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Tambah MOU</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('archieve.mou.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="number_mou">Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number_mou" name="number_mou" placeholder="Nomor MOU"
                            value="{{ old('number_mou') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Judul MOU"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ old('date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Durasi Perjanjian (Tahun) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration"
                            placeholder="Durasi Perjanjian" min="0" value="{{ old('duration') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe MOU <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" name="type" required>
                            <option disabled hidden selected>Pilih Tipe</option>
                            <option value="0" @if (!is_null(old('type')) && old('type') == 0) selected @endif>
                                Korlantas - Kemendikbud
                            </option>
                            <option value="1" @if (!is_null(old('type')) && old('type') == 1) selected @endif>
                                Kewilayahan - Disdik Wilayah
                            </option>
                        </select>
                    </div>
                    <div id="level_form" class="d-none">
                        <div class="form-group">
                            <label for="level">Tingkat Instansi Polri <span class="text-danger">*</span></label>
                            <select class="form-control" id="level" name="level">
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['level'] }}" @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
                                        {{ $level['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="institution_form">
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment[]" id="documentInput"
                            accept=".pdf,.doc,.docx,.txt,.xls,image/*" multiple="true" required>
                        <p class="text-danger py-1">* .pdf .doc .docx .xls .png .jpg .jpeg</p>
                    </div> --}}
                    <div class="form-group">
                        <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment" id="documentInput"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                        {{-- <p class="text-danger py-1">* .pdf .docx .xlsx Max Size 2MB</p> --}}
                        <p class="text-danger py-1">* .pdf .docx .xlsx .pptx</p>
                        <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{{ old('description') }}</textarea>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.mou.index') }}" class="btn btn-sm btn-danger rounded-5">
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
        @include('includes.global.institution_modal')
        @include('js.archieve.mou.script')
        <script>
            $('#documentInput').on('change', function(event) {
                var file = event.target.files[0];
                // if (file.size <= 2000000) {
                //     if (file.type === "application/pdf") {
                //         var fileURL = URL.createObjectURL(file);
                //         $('#documentPreview').attr('src', fileURL);
                //         $('#documentPreview').removeClass('d-none');
                //     } else {
                //         $('#documentPreview').addClass('d-none');
                //         $('#documentPreview').attr('src', '');
                //     }
                // } else {
                //     $('#documentPreview').addClass('d-none');
                //     $('#documentPreview').attr('src', '');
                //     $('#documentInput').val('');
                //     alertError('File Size Lebih Dari 2MB');
                // }
                if (file.type === "application/pdf") {
                    var fileURL = URL.createObjectURL(file);
                    $('#documentPreview').attr('src', fileURL);
                    $('#documentPreview').removeClass('d-none');
                } else {
                    $('#documentPreview').addClass('d-none');
                    $('#documentPreview').attr('src', '');
                }
            });

            $('#type').on('change', function() {
                let level_form = $('#level_form');
                if ($(this).find(":selected").val() == 1) {
                    level_form.removeClass('d-none');
                    $('#level').attr('required', false);
                } else {
                    level_form.addClass('d-none');
                    $('#level').attr('required', false);
                }
            });

            $('#level').on('change', function() {
                $('.institution_form').html('');
                $.ajax({
                    url: '{{ url('institution/get-institution') }}/' + $(this).find(":selected").val() + '/' +
                        1,
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        $('.institution_form').html(data);
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            });
        </script>
    @endpush
@endsection
