@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah MOU</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('archieve.mou.update', ['id' => $mou->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <input type="hidden" id="type_record" value="{{ old('type', $mou->type) }}">
                    <input type="hidden" id="level_record"
                        value="{{ old('type', $mou->type) == 0 ? null : $mou->institution->level }}">
                    <input type="hidden" id="institution_record"
                        value="{{ old('type', $mou->type) == 0 ? null : $mou->institution_id }}">
                    <div class="form-group">
                        <label for="number_mou">Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number_mou" name="number_mou" placeholder="Nomor MOU"
                            value="{{ old('number_mou', $mou->number_mou) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama MOU"
                            value="{{ old('name', $mou->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ old('date', $mou->date) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Durasi Perjanjian (Tahun) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration"
                            placeholder="Durasi Perjanjian" min="0" value="{{ $mou->duration }}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe MOU <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" name="type" required>
                            <option disabled hidden selected>Pilih Tipe</option>
                            <option value="0">
                                Korlantas - Kemendikbud
                            </option>
                            <option value="1">
                                Kewilayahan - Disdik Wilayah
                            </option>
                        </select>
                    </div>
                    <div id="level_form" class="d-none">
                        <div class="form-group">
                            <label for="level">Tingkat Instansi <span class="text-danger">*</span></label>
                            <select class="form-control" id="level" name="level" required>
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    @if ($level['level'] != 1)
                                        <option value="{{ $level['level'] }}">
                                            {{ $level['name'] }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="institution_form">
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="attachment">Lampiran <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment[]" id="documentInput"
                            accept=".pdf,.doc,.docx,.txt,.xls,image/*" multiple="true">
                        <p class="text-danger py-1">* .pdf .docx .xlsx .png .jpg .jpeg</p>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($mou->attachment) as $attachment)
                                    <div class="col-sm-3 col-form-label">
                                        <a target="_blank" href="{{ asset($attachment) }}">Lampiran Surat Keluar<i
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
                        <p class="text-danger py-1">* .pdf .docx .xlsx .pptx (Max 10 MB)</p>
                        <a target="_blank" href="{{ asset($mou->attachment) }}">Lampiran MOU<i
                                class="fas fa-download ml-1"></i></a>
                        <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{!! old('description', $mou->description) !!}</textarea>
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
            let onCreate = true;

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
                        if (onCreate && $('#type').val() == 1) {
                            $('#institution').val($('#institution_record').val()).trigger('change');
                            onCreate = false;
                        }
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            });

            $('#type').val($('#type_record').val()).trigger('change');

            if ($('#type').val() == 1) {
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
