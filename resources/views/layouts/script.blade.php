<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/ionicons/ionicons.js') }}"></script>
<script src="{{ asset('lib/jquery.flot/jquery.flot.js') }}"></script>
<script src="{{ asset('lib/jquery.flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('lib/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('lib/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('lib/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('js/azia.js') }}"></script>
<script src="{{ asset('js/chart.flot.sampledata.js') }}"></script>
<script src="{{ asset('js/dashboard.sampledata.js') }}"></script>

<script src="{{ asset('lib/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('lib/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

@include('js.alert.script')
@stack('js-bottom')
<script>
    $('#description').summernote({
        placeholder: 'Deskripsi',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['font', ['bold', 'underline']],
        ]
    });

    function resetLevel() {
        $('#level').val('').trigger('change');
    }


    $("#form_modal_institution").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Tambah Instansi Data?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mr-2 mb-3',
                cancelButton: 'btn btn-danger rounded-5 mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('master/institution') }}",
                    type: 'POST',
                    cache: false,
                    data: $("#form_modal_institution").serialize(),
                    success: function(data) {
                        $('#level').val($('#level').val()).trigger('change');
                        $('#addInstitution').modal('hide');
                        alertSuccess("Berhasil Menambahkan Instansi");
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    });

    $("#form_modal_type_mail_content").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Tambah Jenis Isi Surat?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mr-2 mb-3',
                cancelButton: 'btn btn-danger rounded-5 mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('master/type-mail-content') }}",
                    type: 'POST',
                    cache: false,
                    data: $("#form_modal_type_mail_content").serialize(),
                    success: function(data) {
                        $('#addTypeMailContent').modal('hide');
                        $('#type_mail_content').empty();

                        data.data.forEach(function(item, index) {
                            $('#type_mail_content').append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                        });

                        alertSuccess("Berhasil Menambahkan Jenis Isi Surat");
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    });
</script>
