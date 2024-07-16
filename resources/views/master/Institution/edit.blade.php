@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Ubah Jenis Isi Surat</h4>
                </div>
                <form class="forms-sample" method="post"
                    action="{{ route('master.institution.update', ['id' => $institution->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="level">Tingkat Instansi Polri <span class="text-danger">*</span></label>
                        <select class="form-control" id="level" name="level" required>
                            <option disabled hidden selected>Pilih Tingkatan</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level['level'] }}">
                                    {{ $level['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="institution_form"></div>
                    <div class="form-group">
                        <label for="name">Nama Instansi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nama Instansi Polri" value="{{ $institution->name }}" required>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('master.institution.index') }}" class="btn btn-sm btn-danger rounded-5">
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
        @include('js.master.institution.script')
        <script>
            let onCreate = true;
            $('#level').on('change', function() {
                $('.institution_form').html('');
                let level = $(this).find(":selected").val() - 1;
                if ($(this).find(":selected").val() == 1 || $(this).find(":selected").val() == 2) {
                    level = 0;
                }
                $.ajax({
                    url: '{{ url('institution/get-institution') }}/' + level + '/0',
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                        $('.institution_form').html(data);
                        if (onCreate) {
                            $('#institution').val({{ $institution->parent_id }}).trigger('change');
                            onCreate = false;
                        }
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            });
        </script>
        @if ($institution->level != 0)
            <script>
                $('#level').val({{ $institution->level }}).trigger('change');
            </script>
        @else
            <script>
                $('#level').val({{ $institution->level }});
            </script>
        @endif
    @endpush
@endsection
