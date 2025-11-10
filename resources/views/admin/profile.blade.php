<!-- resources/views/admin/profile.blade.php -->
@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800" data-aos="fade-right" data-aos-duration="1000">{{ __('Admin Profile') }}</h1>

<div class="card shadow mb-4" data-aos="fade-up" data-aos-duration="1000">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.updateProfileAdmin') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat', $user->alamat) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
        </form>
    </div>
</div>
@endsection