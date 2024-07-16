<div class="form-group">
    <label for="institution">Instansi Polri <span class="text-danger">*</span></label>
    <select class="form-control select2" id="institution_form" name="institution" required>
        @foreach ($institutions as $institution)
            <option value="{{ $institution->id }}" @if (!is_null(old('institution')) && old('institution') == $institution->id) selected @endif>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
</div>
