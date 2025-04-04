@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add User</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control">
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="user">User</option>
            </select>
        </div>

        

        <button type="submit" class="btn btn-success">Create User</button>
    </form>
</div>
@endsection
