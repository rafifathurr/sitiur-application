<div class="form-group">
    <label for="institution">Instansi Polri <span class="text-danger">*</span></label>
    <select class="form-control select2" id="institution_form" name="institution" required>
        <option value="" hidden></option>
        @foreach ($institutions as $institution)
            <option value="{{ $institution->id }}" @if (!is_null(old('institution')) && old('institution') == $institution->id) selected @endif>
                @if ($institution->level > 2)
                    {{ $institution->name }} - {{ $institution->parent->name }}
                @else
                    {{ $institution->name }}
                @endif
            </option>
        @endforeach
    </select>
</div>
<script>
    $('#institution_form').select2({
        placeholder: "Pilih Instansi Polri",
    });
</script>
