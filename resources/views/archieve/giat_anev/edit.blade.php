@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Giat Anev Diseminasi</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('archieve.giat-anev.update', ['id' => $giat_anev->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <input type="hidden" id="level_record"
                        value="{{ !is_null($giat_anev->institution_id) ? $giat_anev->institution->level : $giat_anev->institution_id }}">
                    <input type="hidden" id="institution_record" value="{{ $giat_anev->institution_id }}">
                    <div class="form-group">
                        <label for="number_giat">Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number_giat" name="number_giat" placeholder="Nomor"
                            value="{{ $giat_anev->number_giat }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama"
                            value="{{ $giat_anev->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ $giat_anev->date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Tingkat Instansi Polri</label>
                        <div class="input-group">
                            <select class="form-control" id="level" name="level">
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['level'] }}" @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
                                        {{ $level['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <a class="btn btn-warning" onclick="resetLevel()" title="Reset">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </a>
                        </div>
                        <p class="text-danger py-1">* Diisi Jika Bersumber Dari Instansi Polri</p>
                    </div>
                    <div class="institution_form">
                    </div>
                    {{-- <div class="form-group">
                        <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment[]" id="documentInput"
                            accept=".pdf,.doc,.docx,.txt,.xls,image/*" multiple="true">
                        <p class="text-danger py-1">* .pdf .doc .docx .xls .png .jpg .jpeg</p>
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
                    <div class="form-group">
                        <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment" id="documentInput"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        {{-- <p class="text-danger py-1">* .pdf .docx .xlsx Max Size 2MB</p> --}}
                        <p class="text-danger py-1">* .pdf .docx .xlsx .pptx</p>
                        <a target="_blank" href="{{ asset($giat_anev->attachment) }}">Lampiran Giat Anev Diseminasi<i
                                class="fas fa-download ml-1"></i></a>
                        <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{!! $giat_anev->description !!}</textarea>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.giat-anev.index') }}" class="btn btn-sm btn-danger rounded-5">
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
        @include('js.archieve.giat_anev.script')
        <script>
            let onCreate = true;

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

            $('#level').on('change', function() {
                $('.institution_form').html('');

                if ($(this).find(":selected").val() != undefined) {
                    $.ajax({
                        url: '{{ url('institution/get-institution') }}/' + $(this).find(":selected").val() +
                            '/' +
                            1,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            $('.institution_form').html(data);
                            if (onCreate) {
                                $('#institution').val($('#institution_record').val()).trigger('change');
                                onCreate = false;
                            }
                        },
                        error: function(xhr, error, code) {
                            alertError(error);
                        }
                    });
                }
            });

            if ($('#level_record').val() != '') {
                if ($('#level_record').val() == 0) {
                    $('#level').val(1).trigger('change');
                } else {
                    $('#level').val($('#level_record').val()).trigger('change');
                }
            } else {
                onCreate = false;
            }
        </script>
    @endpush
@endsection
