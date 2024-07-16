<script>
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
        $('#datatable').DataTable({
            autoWidth: false,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
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
                    data: 'number_giat',
                    defaultContent: '-',
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
                    url: '{{ url('archieve/giat-kampung-tertib') }}/' + id,
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
