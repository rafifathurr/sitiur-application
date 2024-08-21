@if (Session::has('success'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get('success') }}',
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    </script>
    @php
        Session::forget('success');
    @endphp
@elseif(Session::has('failed'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: '{{ Session::get('failed') }}',
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    </script>
    @php
        Session::forget('failed');
    @endphp
@endif
<script>
    function alertProcess() {
        Swal.fire({
            title: 'Harap Tunggu...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }

    function alertError(message) {
        Swal.fire({
            icon: 'error',
            title: message,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    }

    function alertWarning(message) {
        Swal.fire({
            icon: 'warning',
            title: message,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    }

    function alertSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            customClass: {
                confirmButton: 'btn btn-primary rounded-5 mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    }
</script>
