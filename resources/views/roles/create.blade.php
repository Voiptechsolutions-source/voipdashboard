@extends('layouts.app')

@section('title', 'Create Roles')

@section('content')
<div class="container">
    <div class="pagetitle mb-4">
        <h1 class="text-primary">Create Role</h1>
    </div>

    <div class="card shadow-lg p-4">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <!-- Role Name -->
            <div class="mb-3">
                <label class="form-label fw-bold">Role Name</label>
                <input type="text" name="name" class="form-control custom-input" required placeholder="Enter role name">
            </div>

            <!-- Permissions Section -->
            <!---<div class="mb-3">
                <h3 class="text-secondary">Permissions</h3>
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" name="permissions[]" 
                                    value="{{ $permission->id }}" 
                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $permission->page_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>-->

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary custom-btn">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
