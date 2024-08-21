<div class="modal fade" id="addTypeMailContent" tabindex="-1" style="z-index: 1051 !important;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" id="form_modal_type_mail_content">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle"><b>Tambah Jenis Isi Surat</b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="redirect" value="true">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nama Jenis Isi Surat" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary rounded-5 mx-2">
                        Simpan
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
