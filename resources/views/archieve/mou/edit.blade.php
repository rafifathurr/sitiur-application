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
                    <input type="hidden" id="type_record" value="{{ $mou->type }}">
                    <input type="hidden" id="level_record" value="{{ $mou->type == 0 ? null : $mou->institution->level }}">
                    <input type="hidden" id="institution_record"
                        value="{{ $mou->type == 0 ? null : $mou->institution_id }}">
                    <div class="form-group">
                        <label for="number_mou">Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number_mou" name="number_mou" placeholder="Nomor MOU"
                            value="{{ $mou->number_mou }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama MOU"
                            value="{{ $mou->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ $mou->date }}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe MOU <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" name="type" required>
                            <option disabled hidden selected>Pilih Tipe</option>
                            <option value="0">
                                Korlantas - Kemendikbud
                            </option>
                            <option value="1">
                                Kewilayahan
                            </option>
                        </select>
                    </div>
                    <div id="level_form" class="d-none">
                        <div class="form-group">
                            <label for="level">Tingkat Instansi <span class="text-danger">*</span></label>
                            <select class="form-control" id="level" name="level" required>
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['level'] }}">
                                        {{ $level['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="institution_form">
                    </div>
                    <div class="form-group">
                        <label for="attachment">Lampiran MOU <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment[]" id="documentInput"
                            accept=".pdf,.doc,.docx,.txt,.xls,image/*" multiple="true">
                        <p class="text-danger py-1">* .pdf .doc .docx .xls .png .jpg .jpeg</p>
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
                        {{-- <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe> --}}
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{!! $mou->description !!}</textarea>
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

            $('#type').val($('#type_record').val()).trigger('change');

            // $('#documentInput').on('change', function(event) {
            //     var file = event.target.files[0];
            //     if (file.type === "application/pdf") {
            //         var fileURL = URL.createObjectURL(file);
            //         $('#documentPreview').attr('src', fileURL);
            //         $('#documentPreview').removeClass('d-none');
            //     } else {
            //         $('#documentPreview').addClass('d-none');
            //         $('#documentPreview').attr('src', '');
            //     }
            // });

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
                        if (onCreate && $('#type') == 1) {
                            $('#institution').val($('#institution_record').val()).trigger('change');
                            onCreate = false;
                        }
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            });

            if ($('#type') == 1) {
                $('#level').val($('#level_record').val()).trigger('change');
            } else {
                onCreate = false;
            }
        </script>
    @endpush
@endsection
