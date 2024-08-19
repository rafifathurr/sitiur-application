<script>
    $('#documentInput').on('change', function(event) {
        var file = event.target.files[0];
        if (file.size <= 10000000) {
            var file = event.target.files[0];
            var videoPreview = $('#videoPreview');
            var fileURL = URL.createObjectURL(file);
            videoPreview.attr('src', fileURL);
            videoPreview[0].load();
        } else {
            $('#videoPreview').attr('src', '');
            $('#documentInput').val('');
            alertError('Ukuran File Lebih Dari 10MB');
        }
    });

    $(".forms-sample").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Simpan Data?',
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
                alertProcess();
                $('.forms-sample').unbind('submit').submit();
            }
        })
    });

    function dataTable() {
        const url = $('#datatable-url').val();
        let year = $('#year').val();

        let table = $('#datatable').DataTable({
            destroy: true
        });

        table.rows().remove().draw();

        $('#datatable').DataTable({
            autoWidth: false,
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: url,
                data: {
                    year: year,
                },
                error: function(xhr, error, code) {
                    alertError(xhr.statusText);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%',
                    searchable: false
                },
                {
                    data: 'date',
                    defaultContent: '-',
                },
                {
                    data: 'institution',
                    defaultContent: '-',
                },
                {
                    data: 'name',
                    defaultContent: '-',
                },
                {
                    data: 'action',
                    className: 'text-center',
                    width: '25%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    }

    function destroyRecord(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Hapus Data?',
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
                alertProcess();
                $.ajax({
                    url: '{{ url('archieve/documentation') }}/' + id,
                    type: 'DELETE',
                    cache: false,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        alertError(error);
                    }
                });
            }
        })
    }
</script>
