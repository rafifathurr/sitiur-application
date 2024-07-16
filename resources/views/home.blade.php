@extends('layouts.main')
@section('content')
    <div class="page mt-0">
        <div class="blurred-bg"></div>
        <div class="content">
            <img src="{{ asset('img/korlantas-mabes.png') }}" alt="" width="50%">
            <h1 class="font-weight-bold mt-2">Selamat Datang di Situs <br> SITIUR KORLANTAS</h1>
        </div>
    </div>
    <div class="az-content az-content-dashboard">
        <div class="container py-5">
            <div class="az-content-body">
                <div class="az-dashboard-one-title justify-content-center">
                    <h2 class="font-weight-bold text-center" id="title">
                        <center>Daftar Pejabat Utama</center>
                    </h2>
                </div>
                <div class="my-5">
                    <div class="row">
                        <div class="col-md-4 my-5">
                            <div class="col-md-12 mx-auto text-center">
                                <img src="{{ asset('img/irjen-aan-suhanan.jpg') }}" alt="" width="75%"
                                    height="326px" class="rounded-5 border border-1-grey">
                            </div>
                            <div class="col-md-12 mt-3">
                                <h3 class="font-weight-bold text-center">KAKORLANTAS</h3>
                                <h5 class="text-center">Irjen. Pol. Dr. Drs. Aan Suhanan,
                                    M.Si.</h5>
                            </div>
                        </div>
                        <div class="col-md-4 my-5">
                            <div class="col-md-12 mx-auto text-center">
                                <img src="{{ asset('img/brigjen-bakharuddin-dirkamsel.png') }}" alt=""
                                    width="75%" height="326px" class="rounded-5 border border-1-grey">
                            </div>
                            <div class="col-md-12 mt-3">
                                <h3 class="font-weight-bold text-center">DIRKAMSEL</h3>
                                <h5 class="text-center">Brigjen. Pol. Dr. Bakharuddin Muhammad Syah,
                                    S.H.,
                                    S.I.K., M.Si.</h5>
                            </div>
                        </div>
                        <div class="col-md-4 my-5">
                            <div class="col-md-12 mx-auto text-center">
                                <img src="{{ asset('img/kombes-arman-achdiat.jpg') }}" alt="" width="75%"
                                    height="326px" class="rounded-5 border border-1-grey">
                            </div>
                            <div class="col-md-12 mt-3">
                                <h3 class="font-weight-bold text-center">KASUBDIT DIKMAS</h3>
                                <h5 class="text-center">KOMBES POL ARMAN ACHDIAT, S.I.K., M.Si.</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 py-5">
            <div class="container">
                <div class="az-content-body">
                    <div class="az-dashboard-one-title justify-content-center">
                        <h2 class="font-weight-bold text-center" id="title">
                            <center>Visi dan Misi <br>KORLANTAS POLRI</center>
                        </h2>
                    </div>
                    <div class="col-md-12 text-center">
                        <img src="{{ asset('img/korlantas-mabes.png') }}" width="35%" alt="">
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <div class="card border-0 rounded-5">
                                <div class="card-header bg-gray-200">
                                    <h3 class="font-weight-bold">Visi</h3>
                                </div>
                                <div class="card-body">
                                    Polisi Lalu Lintas (Polantas) Indonesia adalah mewujudkan pelayanan kamseltibcarlantas
                                    yang
                                    prima
                                    dan unggul melalui penegakan hukum yang tegas, adil, dan humanis; dalam rangka
                                    meningkatkan
                                    kesadaran dan kepatuhan berlalu lintas, serta terjalinnya sinergi polisional yang
                                    proaktif,
                                    dalam
                                    rangka memantapkan situasi keamanan dalam negeri.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 my-3">
                            <div class="card border-0 rounded-5">
                                <div class="card-header bg-gray-200">
                                    <h3 class="font-weight-bold">Misi</h3>
                                </div>
                                <div class="card-body">
                                    <ol class="text-left">
                                        <li>Memelihara dan mewujudkan pelayanan keamanan, keselamatan, ketertiban dan
                                            kelancaran
                                            lalu lintas yang prima dan unggul secara cepat, tepat, efisien, transparan dan
                                            akuntabel.</li>
                                        <li>Menjaga keamanan, ketertiban, dan kelancaran lalu lintas dalam rangka menangkal
                                            dan
                                            mencegah potensi gangguan keamanan di jalan, serta menjamin keselamatan dan
                                            kelancaran arus lalu lintas orang dan barang.</li>
                                        <li>Melaksanakan penegakan hukum secara profesional, objektif, proposional, dan
                                            efektif
                                            di bidang lalu lintas; serta memberikan perlindungan, pengayoman dan pelayanan
                                            secara mudah, transparan, akuntabel, dan tidak diskriminatif, dalam rangka
                                            menjamin
                                            kepastian hukum dan rasa keadilan.</li>
                                        <li>Mengembangkan sinergitas polisional yang proaktif berbasis pada masyarakat patuh
                                            dan
                                            sadar hukum.</li>
                                        <li>Menjamin keberhasilan penanggulangan permasalahan lalu lintas guna meningkatkan
                                            laju
                                            pertumbuhan ekonomi dan stabilitas keamanan dalam negeri.</li>
                                        <li>Mengelola secara profesional, transparan, akuntabel, dan modern seluruh sumber
                                            daya
                                            Polantas guna mendukung tugas Polri.</li>
                                        <li>Mewujudkan budaya tertib lalu lintas dengan terus melaksanakan kampanye pelopor
                                            keselamatan berlalu lintas.</li>
                                        <li>Mengembangkan sarana dan prasarana di bidang lalu lintas sesuai tuntutan dan
                                            harapan
                                            masyakarat, serta optimalisasi pusat kendali sistem informasi dan komunikasi
                                            lalu
                                            lintas angkutan jalan dalam rangka menjamin pelayanan keamanan, keselamatan,
                                            ketertiban dan kelancaran berlalu lintas kepada masyarakat.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-5">
            <div class="container">
                <div class="az-content-body">
                    <div class="az-dashboard-one-title justify-content-center">
                        <h2 class="font-weight-bold text-center" id="title">
                            <center>Tugas dan Fungsi</center>
                        </h2>
                    </div>
                    <div class="col-md-12 text-center">
                        <img src="{{ asset('img/korlantas-mabes.png') }}" width="35%" alt="">
                    </div>
                    <div class="card rounded-5 my-3">
                        <div class="card-header bg-gray-200">
                            <h3 class="font-weight-bold text-center my-1">KORLANTAS POLRI</h3>
                        </div>
                        <div class="card-body bg-gray-100">
                            <div class="row">
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Tugas</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Membina dan menyelenggarakan fungsi lalu lintas yang meliputi pendidikan
                                                    masyarakat, penegakan hukum, pengkajian masalah lalu lintas, registrasi
                                                    dan identifikasi pengemudi dan kendaraan bermotor serta patroli jalan
                                                    raya;</li>
                                                <li>Melaksanakan penertiban lalu lintas, manajemen operasional dan rekayasa
                                                    lalu lintas (engineering);</li>
                                                <li>Menyelenggarakan pusat Komunikasi, Koordinasi, Kendali dan Informasi
                                                    (K3I) tentang lalu lintas;</li>
                                                <li>Mengkoordinasikan pemangku kepentingan yang berkaitan dengan
                                                    penyelenggaraan lalu lintas;</li>
                                                <li>Memberikan rekomendasi dampak lalu lintas; dan</li>
                                                <li>Melaksanakan koordinasi dan/atau pengawasan PPNS.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Fungsi</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Penyusunan kebijakan strategis yang berkaitan dengan peran dan fungsi
                                                    polisi lalu lintas, perumusan dan atau pengembangan sistem dan metode
                                                    termasuk petunjuk pelaksanaan fungsi lalu lintas, membangun kemitraan
                                                    dan kerjasama baik dalam maupun luar negeri, serta menyelenggarakan
                                                    koordinasi dengan pemangku kepentingan lainnya di bidang lalu lintas;
                                                </li>
                                                <li>Pelaksanaan manajemen operasional lalu lintas yang meliputi kegiatan
                                                    memelihara dan mewujudkan keamanan, keselamatan, ketertiban dan
                                                    kelancaran lalu lintas di jalan raya, jalan tol, serta jalan-jalan luar
                                                    kota sebagai penghubung (linking ping) antar kesatuan lalu lintas
                                                    melalui kegiatan pengaturan, penjagaan, pengawalan, patroli, TPKP, Quick
                                                    Respon Time, dan menjadi jejaring National Traffic Management Centre
                                                    (NTMC);</li>
                                                <li>Pengembangan sistem dan metode termasuk petunjuk pelaksanaan teknis
                                                    penegakan hukum yang meliputi kegiatan penindakan terhadap pelanggaran
                                                    aturan lalu lintas, penanganan kecelakaan lalu lintas,penyidikan
                                                    kecelakaan lalu lintas, serta koordinasi dan pengawasan PPNS;</li>
                                                <li>Pendidikan masyarakat dalam berlalu lintas, melalui kegiatan
                                                    sosialisasi, penanaman nilai, membangun kesadaran, kepekaan, kepedulian
                                                    akan tertib lalu lintas, serta pendidikan berlalu lintas secara formal
                                                    dan informal;</li>
                                                <li>Pembinaan teknis dan administrasi registrasi serta identifikasi
                                                    pengemudi dan kendaraan bermotor yang meliputi kegiatan pengecekan
                                                    administrasi dan fisik kendaraan serta pengujian kompetensi pengemudi
                                                    untuk menjamin keabsahan dokumen kendaraan bermotor dan sarana kontrol
                                                    dalam rangka penegakan hukum maupun untuk kepentingan forensik
                                                    kepolisian;</li>
                                                <li>Pengkajian bidang lalu lintas yang meliputi kegiatan keamanan dan
                                                    keselamatan lalu lintas, pemetaan, inventarisasi, identifikasi wilayah,
                                                    masalah maupun potensi-potensi yang berkaitan dengan lalu lintas dalam
                                                    sistem Filling and Recording, baik untuk kepentingan internal maupun
                                                    eksternal kepolisian, serta perumusan rekomendasi dampak lalu lintas;
                                                    dan</li>
                                                <li>Pelaksanaan operasional NTMC, yang meliputi kegiatan pengumpulan,
                                                    pengolahan dan penyajian data lalu lintas, sebagai pusat kendali,
                                                    koordinasi, komunikasi, dan informasi, pengembangan sistem dan teknologi
                                                    informasi dan komunikasi lalu lintas, serta pelayanan informasi lalu
                                                    lintas yang menyangkut pelanggaran dan kecelakaan lalu lintas dengan
                                                    lingkup data jajaran Polri.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-5 my-3">
                        <div class="card-header bg-gray-200">
                            <h3 class="font-weight-bold text-center my-1">DIRKAMSEL KORLANTAS POLRI</h3>
                        </div>
                        <div class="card-body bg-gray-100">
                            <div class="row">
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Tugas</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Mensosialisasikan, menanamkan, mengajak, mendidik, membangun kesadaran,
                                                    kepekaan, kepedulian akan tertib lalu lintas yang diselenggarakan secara
                                                    formal dan non formal;</li>
                                                <li>Melakukan pengkajian dalam mengoperasionalkan fungsi rekayasa lalu
                                                    lintas baik untuk kepentingan Internal Kepolisian maupun Eksternal
                                                    Kepolisian;</li>
                                                <li>Menyusun standar dan prosedur dalam memetakan (inventarisisasi dan
                                                    mengidentifikasi) wilayah, masalah, maupun potensi yang berkaitan dengan
                                                    lalu lintas dalam sistem; dan</li>
                                                <li>Melaksanakan pemeriksaan dan audit dalam mengembangkan dan
                                                    mengoperasionalkan hasil kajian dan rekomendasi terhadap berbagai
                                                    masalah yang berkaitan dengan Kamseltibcarlantas.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Fungsi</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Penetapan norma, standar, pedoman, kriteria, prosedur, sasaran dan arah
                                                    kebijakan pengembangan pendidikan masyarakat berlalu lintas;</li>
                                                <li>Penetapan norma, standar, pedoman, kriteria, prosedur, sasaran dan arah
                                                    kebijakan pengembangan penyelenggaraan dan rekayasa lalu lintas;</li>
                                                <li>Penetapan kompetensi pejabat yang melaksanakan fungsi di bidang
                                                    manajemen dan rekayasa lalu lintas;
                                                </li>
                                                <li>
                                                    Pemberian bimbingan, pelatihan, sertifikasi, pemberian izin, dan bantuan
                                                    teknis kepada Polantas daerah di bidang manajemen dan rekayasa lalu
                                                    lintas; dan
                                                </li>
                                                <li>Pengawasan pelaksanaan norma, standar, pedoman, kriteria, prosedur dan
                                                    kegiatan audit di bidang lalu lintas.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-5 my-3">
                        <div class="card-header bg-gray-200">
                            <h3 class="font-weight-bold text-center my-1">SUBDIT DIKMAS KORLANTAS POLRI</h3>
                        </div>
                        <div class="card-body bg-gray-100">
                            <div class="row">
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Tugas</h3>
                                        </div>
                                        <div class="card-body">
                                            Subdit Dikmas, bertugas mensosialisasikan, menanamkan, mengajak, mendidik,
                                            membangun
                                            kesadaran, kepekaan, kepedulian akan tertib Lalu Lintas yang di selenggarakan
                                            secara formal
                                            dan non formal
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Fungsi</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Pemberian bimbingan, pelatihan, pemberian izin, dan bantuan teknis
                                                    kepada pemerintah
                                                    Kabupaten/Kota dalam bidang pendidikan masyarakat berlalu lintas;</li>
                                                <li>Pengawasan pelaksanaan norma standar, pedoman, criteria, dan prosedur
                                                    Pendidikan
                                                    Masyarakat Berlalu Lintas oleh Pemerintah Daerah;</li>
                                                <li>Penetapan kompetensi pejabat yang melaksanakan fungsi di bidang
                                                    pendidikan
                                                    masyarakat berlalu lintas; dan</li>
                                                <li>Pendataan dan pengkajian kegiatan dikmas lantas guna menyusun produk
                                                    dalam
                                                    mendukung kegiatan dikmas lantas.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-5 my-3">
                        <div class="card-header bg-gray-200">
                            <h3 class="font-weight-bold text-center my-1">KEMITRAAN SUBDIT DIKMAS
                                KORLANTAS POLRI</h3>
                        </div>
                        <div class="card-body bg-gray-100">
                            <div class="row">
                                <div class="col-md-12 my-3">
                                    <div class="card border-0 rounded-5">
                                        <div class="card-header bg-gray-200">
                                            <h3 class="font-weight-bold">Tugas dan Fungsi</h3>
                                        </div>
                                        <div class="card-body">
                                            <ol class="text-left">
                                                <li>Membangun kerja sama dengan pembina dan penyelenggara Lalu Lintas dan
                                                    Angkutan Jalan dalam rangka melaksanakan Dikmas Lantas;</li>
                                                <li>Membangun Kemitraan dengan sekolah disemua tingkatan dan kelompok
                                                    masyarakat dalam menyelenggarakan Dikmas Lantas; dan</li>
                                                <li>Melaksanakan Diseminasi Pengetahuan Lalu Lintas (PLL) masuk dalam
                                                    kurikulum mata pelajaran Pendidikan Kewarganegaraan.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 py-5">
            <div class="container">
                <div class="az-content-body">
                    <div class="az-dashboard-one-title justify-content-center">
                        <h2 class="font-weight-bold text-center" id="title">
                            <center>Struktur Organisasi</center>
                        </h2>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-3">
                            <div class="card rounded-5 border-0">
                                <div class="card-body text-center bg-gray-100 p-0">
                                    <h4 class="font-weight-bold py-2">Struktur Organisasi <br> KORLANTAS POLRI</h4>
                                    <img src="{{ asset('img/korlantas-struktur.png') }}"
                                        class="rounded-5 border border-1-grey" width="90%" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 py-5">
                            <div class="card rounded-5 border-0">
                                <div class="card-body text-center bg-gray-100 p-0">
                                    <h4 class="font-weight-bold py-2">Struktur Organisasi <br> DIRKAMSEL KORLANTAS
                                        POLRI</h4>
                                    <img src="{{ asset('img/dirkamsel-struktur.png') }}"
                                        class="rounded-5 border border-1-grey" width="90%" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 py-5">
                            <div class="card rounded-5 border-0">
                                <div class="card-body text-center bg-gray-100 p-0">
                                    <h4 class="font-weight-bold py-2">Struktur Organisasi <br> SUBDIT DIKMAS KORLANTAS
                                        POLRI</h4>
                                    <img src="{{ asset('img/subditdikmas-struktur.jpeg') }}"
                                        class="rounded-5 border border-1-grey" width="90%" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 py-5">
                            <div class="card rounded-5 border-0">
                                <div class="card-body text-center bg-gray-100 p-0">
                                    <h4 class="font-weight-bold py-2">Struktur Organisasi <br> KEMITRAAN SUBDIT DIKMAS
                                        KORLANTAS
                                        POLRI</h4>
                                    <img src="{{ asset('img/kasi-kemitraan-stuktur.png') }}"
                                        class="rounded-5 border border-1-grey" width="90%" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-5">
            <div class="container">
                <div class="az-content-body">
                    <div class="az-dashboard-one-title justify-content-center">
                        <h2 class="font-weight-bold text-center" id="title">
                            <center>Galeri Kegiatan</center>
                        </h2>
                    </div>
                    @if (count($gallery) > 0)
                        <div id="myCarousel" class="carousel carousel-dark slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                @foreach ($gallery as $index => $item)
                                    <li data-target="#myCarousel" data-slide-to="{{ $index }}"
                                        @if ($index == 0) class="active" @endif> </li>
                                @endforeach
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                @foreach ($gallery as $item)
                                    <div class="carousel-item @if ($index == 0) active @endif"
                                        data-interval="10">
                                        <img src="{{ asset($item->attachment) }}" alt="Los Angeles" style="width:100%;">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>{{ $item->name }}</h5>
                                            <p>{{ date('d F Y', strtotime($item->date)) }}</p>
                                            <p>{!! $item->description !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Left and right controls -->
                            <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    @else
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
