@extends('layouts.main')
@section('content')
    <div class="az-content az-content-dashboard mb-5">
        <div class="container">
            <div class="az-content-body">
                <div class="az-dashboard-one-title">
                    <h4 class="az-dashboard-title" id="title">Tambah User</h4>
                </div>
                <form class="forms-sample" method="post" action="{{ route('user-management.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                            value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="roles">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="roles" name="roles" required>
                            <option disabled hidden selected>Pilih Role</option>
                            @foreach ($roles as $role)
                                @if (!is_null(old('roles')) && old('roles') == $role->name)
                                    <option value="{{ $role->name }}" selected>{{ $role->name }}</option>
                                @else
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="re_password">Ulangi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="re_password" name="re_password"
                            placeholder="Ulangi Password" required>
                    </div>
                    <div class="text-right mt-5">
                        <a href="{{ route('user-management.index') }}" class="btn btn-sm btn-danger rounded-5">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary rounded-5 mx-2">
                            Simpan
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </form>
                <!-- az-dashboard-one-title -->
            </div>
            <!-- az-content-body -->
        </div>
    </div>
    @push('js-bottom')
        @include('js.user_management.script')
    @endpush
@endsection
