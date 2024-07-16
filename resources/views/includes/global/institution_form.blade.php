<div class="form-group">
    <label for="institution">Instansi Polri <span class="text-danger">*</span></label>
    <div class="input-group">
        <select class="form-control" id="institution" name="institution" required>
            @foreach ($institutions as $institution)
                <option value="{{ $institution->id }}" @if (!is_null(old('institution')) && old('institution') == $institution->id) selected @endif>
                    {{ $institution->name }}
                </option>
            @endforeach
        </select>
        <span class="input-group-text bg-primary text-white" id="" title="Tambah Instansi" data-toggle="modal"
            data-target="#addInstitution">
            <i class="fas fa-plus mr-1"></i> Tambah Instansi
        </span>
    </div>
</div>
<script>
    $('#institution').select2();
</script>
