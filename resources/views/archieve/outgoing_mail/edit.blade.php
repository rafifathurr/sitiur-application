@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Surat Keluar</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('archieve.outgoing-mail.update', ['id' => $outgoing_mail->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <input type="hidden" id="level_record"
                        value="{{ !is_null($outgoing_mail->institution_id) ? $outgoing_mail->institution->level : $outgoing_mail->institution_id }}">
                    <input type="hidden" id="institution_record" value="{{ $outgoing_mail->institution_id }}">
                    <div class="form-group">
                        <label for="number">Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="number" name="number" placeholder="Nomor"
                            value="{{ old('number', $outgoing_mail->number) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Judul Surat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Judul Surat"
                            value="{{ old('name', $outgoing_mail->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ old('date', $outgoing_mail->date) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="classification">Klasifikasi <span class="text-danger">*</span></label>
                        <select class="form-control" id="classification" name="classification">
                            <option disabled hidden selected>Pilih Klasifikasi</option>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification->id }}" @if (old('classification_id', $outgoing_mail->classification_id) == $classification->id) selected @endif>
                                    {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type_mail_content">Jenis Isi Surat <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-control" id="type_mail_content" name="type_mail_content">
                                <option disabled hidden selected>Pilih Jenis Isi Surat</option>
                                @foreach ($type_mail_contents as $type_mail_content)
                                    <option value="{{ $type_mail_content->id }}"
                                        @if (old('type_mail_content', $outgoing_mail->type_mail_content_id) == $type_mail_content->id) selected @endif>
                                        {{ $type_mail_content->name }}
                                    </option>
                                @endforeach
                            </select>
                            <a class="btn btn-primary" title="Tambah Jenis" data-toggle="modal"
                                data-target="#addTypeMailContent">
                                <i class="fas fa-plus mr-1"></i> Tambah Jenis
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="level">Tingkat Instansi Polri</label>
                        <div class="input-group">
                            <select class="form-control" id="level" name="level">
                                <option disabled hidden selected>Pilih Tingkatan</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level['level'] }}"
                                        @if (!is_null(old('level')) && old('level') == $level['level']) selected @endif>
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
                        <p class="text-danger py-1">* .pdf .docx .xlsx .png .jpg .jpeg</p>
                        <div class="p-1">
                            <div class="row">
                                @foreach (json_decode($outgoing_mail->attachment) as $attachment)
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
                        <a target="_blank" href="{{ asset($outgoing_mail->attachment) }}">Lampiran Surat Keluar<i
                                class="fas fa-download ml-1"></i></a>
                        <iframe id="documentPreview" class="w-100 mt-3 d-none" style="height: 600px;"></iframe>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{!! old('description', $outgoing_mail->description) !!}</textarea>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('archieve.outgoing-mail.index') }}" class="btn btn-sm btn-danger rounded-5">
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
        @include('includes.global.type_mail_content_modal')
        @include('includes.global.institution_modal')
        @include('js.archieve.outgoing_mail.script')
        <script>
            let onCreate = true;

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
