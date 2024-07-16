<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-90680653-2"></script>

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>SITIUR KORLANTAS</title>

    <!-- vendor css -->
    <link href="{{ asset('lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/typicons.font/typicons.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('lib/datatables.net-bs4/dataTables.bootstrap4.css') }}" />

    <!-- azia CSS -->
    <link rel="stylesheet" href="{{ asset('css/azia.css') }}" />

    <link rel="shortcut icon" href="{{ asset('img/korlantas.png') }}" />

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .blurred-bg {
            position: relative;
            width: 100%;
            height: 90vh;
            background-image: url('img/bg-korlantas-3.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(7px) brightness(60%);
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
        }
    </style>
</head>