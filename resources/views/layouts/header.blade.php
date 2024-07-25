<div class="az-header">
    <div class="container">
        <div class="az-header-left">
            <a href="{{ url('/') }}" class="text-dark font-weight-bold h4 mx-auto mb-0"><img
                    src="{{ asset('img/korlantas-mabes.png') }}" width="15%" class="" alt="">APDA
                KORLANTAS</a>
            <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div>
        <!-- az-header-left -->
        <div class="az-header-menu">
            <div class="az-header-menu-header">
                <a href="{{ url('/') }}" class="text-dark font-weight-bold mx-auto"><img
                        src="{{ asset('img/korlantas-mabes.png') }}" width="15%" class="" alt="">APDA
                    KORLANTAS</a>
                <a href="" class="close">&times;</a>
            </div>
            <!-- az-header-menu-header -->
            <ul class="nav">
                <li class="nav-item @if (Route::currentRouteName() == 'home') active show @endif">
                    <a href="{{ url('/') }}" class="nav-link"><i class="typcn typcn-home"></i> Beranda</a>
                </li>
                @if (Illuminate\Support\Facades\Auth::check())
                    @if (Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                        <li class="nav-item @if (in_array(Route::currentRouteName(), [
                                'archieve.mou.index',
                                'archieve.giat-kampung-tertib.index',
                                'archieve.giat-anev.index',
                                'archieve.incoming-mail.index',
                                'archieve.outgoing-mail.index',
                                'archieve.statement-letter.index',
                                'archieve.documentation.index',
                                'archieve.gallery.index',
                            ])) active show @endif">
                            <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Arsip
                            </a>
                            <nav class="az-menu-sub">
                                <a href="{{ route('archieve.mou.index') }}" class="nav-link">MOU</a>
                                <a href="{{ route('archieve.giat-kampung-tertib.index') }}" class="nav-link">Giat
                                    Kampung Tertib</a>
                                <a href="{{ route('archieve.giat-anev.index') }}" class="nav-link">Giat Anev
                                    Diseminasi</a>
                                <a href="{{ route('archieve.incoming-mail.index') }}" class="nav-link">Surat Masuk</a>
                                <a href="{{ route('archieve.outgoing-mail.index') }}" class="nav-link">Surat Keluar</a>
                                <a href="{{ route('archieve.statement-letter.index') }}" class="nav-link">Surat
                                    Pernyataan</a>
                                <a href="{{ route('archieve.documentation.index') }}" class="nav-link">Dokumentasi
                                    Video</a>
                                <a href="{{ route('archieve.gallery.index') }}" class="nav-link">Galeri</a>
                            </nav>
                        </li>
                        <li class="nav-item @if (in_array(Route::currentRouteName(), [
                                'master.classification.index',
                                'master.type-mail-content.index',
                                'master.institution.index',
                            ])) active show @endif">
                            <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Master
                            </a>
                            <nav class="az-menu-sub">
                                <a href="{{ route('master.institution.index') }}" class="nav-link">Instansi Polri</a>
                                <a href="{{ route('master.type-mail-content.index') }}" class="nav-link">Jenis Isi
                                    Surat</a>
                                <a href="{{ route('master.classification.index') }}" class="nav-link">Klasifikasi
                                    Surat</a>
                            </nav>
                        </li>
                        <li class="nav-item @if (Route::currentRouteName() == 'user-management.index') active show @endif">
                            <a href="{{ route('user-management.index') }}" class="nav-link"><i
                                    class="typcn typcn-user"></i>
                                User</a>
                        </li>
                    @elseif(Illuminate\Support\Facades\Auth::user()->hasRole('user'))
                        <li class="nav-item @if (in_array(Route::currentRouteName(), [
                                'archieve.mou.index',
                                'archieve.giat-kampung-tertib.index',
                                'archieve.giat-anev.index',
                                'archieve.incoming-mail.index',
                                'archieve.outgoing-mail.index',
                                'archieve.statement-letter.index',
                                'archieve.documentation.index',
                                'archieve.gallery.index',
                            ])) active show @endif">
                            <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Arsip
                            </a>
                            <nav class="az-menu-sub">
                                <a href="{{ route('archieve.mou.index') }}" class="nav-link">MOU</a>
                                <a href="{{ route('archieve.giat-kampung-tertib.index') }}" class="nav-link">Giat
                                    Kampung Tertib</a>
                                <a href="{{ route('archieve.giat-anev.index') }}" class="nav-link">Giat Anev
                                    Diseminasi</a>
                                <a href="{{ route('archieve.documentation.index') }}" class="nav-link">Dokumentasi
                                    Video</a>
                                <a href="{{ route('archieve.incoming-mail.index') }}" class="nav-link">Surat Masuk</a>
                                <a href="{{ route('archieve.outgoing-mail.index') }}" class="nav-link">Surat Keluar</a>
                            </nav>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link"><i class="typcn typcn-user"></i>
                            Login</a>
                    </li>
                @endif
            </ul>
        </div>
        @if (Illuminate\Support\Facades\Auth::check())
            <!-- az-header-menu -->
            <div class="az-header-right">
                <!-- az-header-notification -->
                <div class="dropdown az-profile-menu">
                    <a href="" class="az-img-user"><img src="{{ asset('img/presisisi.png') }}"
                            alt="" /></a>
                    <div class="dropdown-menu">
                        <div class="az-dropdown-header d-sm-none">
                            <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                        </div>
                        <div class="az-header-profile">
                            <div class="az-img-user">
                                <img src="{{ asset('img/presisisi.png') }}" alt="" />
                            </div>
                            <!-- az-img-user -->
                            <h6>{{ Auth::user()->name }}</h6>
                            @php
                                $exploded_raw_role = explode('-', Auth::user()->getRoleNames()[0]);
                                $user_role = ucwords(implode(' ', $exploded_raw_role));
                            @endphp
                            <span>{{ $user_role }}</span>
                        </div>
                        <!-- az-header-profile -->

                        <a href="{{ route('logout') }}" class="dropdown-item"><i
                                class="typcn typcn-power-outline"></i>
                            Logout</a>
                    </div>
                    <!-- dropdown-menu -->
                </div>
            </div>
        @endif
        <!-- az-header-right -->
    </div>
    <!-- container -->
</div>
