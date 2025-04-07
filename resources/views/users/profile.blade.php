@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Your Profile</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" class="form-control" value="{{ $user->username }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label>Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Update Password</button>
        </form>
    </div>
@endsection