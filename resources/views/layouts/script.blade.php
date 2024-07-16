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
</script>
