@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Dokumentasi Video</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('archieve.documentation.update', ['id' => $documentation->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <input type="hidden" id="level_record"
                        value="{{ !is_null($documentation->institution_id) ? $documentation->institution->level : $documentation->institution_id }}">
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama"
                            value="{{ old('name', $documentation->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal"
                            value="{{ old('date', $documentation->date) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Tingkat Instansi <span class="text-danger">*</span></label>
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
                    <div class="form-group custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="has_link_attachment"
                            id="has_link_attachment" onchange="showFormLink(this)"
                            @if (!is_null($documentation->link_attachment)) checked @endif>
                        <label class="custom-control-label" for="has_link_attachment">Link Lampiran</label>
                    </div>
                    <div id="link_attachment_form" @if (is_null($documentation->link_attachment)) class="d-none" @endif>
                        <div class="form-group">
                            <label for="attachment">Link Lampiran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="link_attachment" name="link_attachment"
                                @if (!is_null($documentation->link_attachment)) value="{{ $documentation->link_attachment }}" @endif>
                            <p class="text-danger py-1">* Link Url Youtube</p>
                        </div>
                        <div class="form-group">
                            <iframe @if (!is_null($documentation->link_attachment)) src="{{ implode('embed/', explode('watch?v=', $documentation->link_attachment)) }}" @endif
                                id="streamPreview" class="w-100 mt-3" style="height: 700px;"></iframe>
                        </div>
                    </div>
                    <div id="upload_attachment_form" @if (!is_null($documentation->link_attachment)) class="d-none" @endif>
                        <div class="form-group">
                            <label for="attachment">Lampiran Video <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="documentInput" name="attachment" accept="video/*"
                                required>
                            <p class="text-danger py-1">* .mov .mp4 (Max 10 MB)</p>
                        </div>
                        <div class="form-group">
                            <video id="videoPreview" class="w-100 mt-3" controls>
                                @if (is_null($documentation->link_attachment))
                                    <source src="{{ asset($documentation->attachment) }}" type="video/mp4">
                                @endif
                            </video>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                            placeholder="Deskripsi">{!! old('description', $documentation->description) !!}</textarea>
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
        @include('includes.global.institution_modal')
        @include('js.archieve.documentation.script')
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
                                $('#institution').val({{ $documentation->institution_id }}).trigger(
                                    'change');
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
