@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Email Template</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('email-templates.update', $emailTemplate->id) }}" method="POST" id="templateForm" onsubmit="return validateForm()">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Template Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $emailTemplate->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $emailTemplate->subject) }}" required>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            <input type="hidden" name="body" id="hiddenBody" value="{{ old('body', $emailTemplate->body) }}">
            <textarea id="body" class="form-control" rows="10">{{ old('body', $emailTemplate->body) }}</textarea>
            <small class="text-muted">Use placeholders like {username}, {email}, {date}</small>
            <div class="mt-2">
                <h4>Insert Placeholder:</h4>
                <select id="placeholderSelect" class="form-control w-50 d-inline-block">
                    <option value="">Select a placeholder</option>
                    @foreach($placeholders as $placeholder => $description)
                        <option value="{{ $placeholder }}">{{ $placeholder }} - {{ $description }}</option>
                    @endforeach
                </select>
                <button type="button" id="insertPlaceholder" class="btn btn-secondary ml-2">Insert</button>
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update Template</button>
    </form>
    <div class="mt-3">
        <h4>Available Placeholders:</h4>
        <ul>
            @foreach($placeholders as $placeholder => $description)
                <li>{{ $placeholder }} - {{ $description }}</li>
            @endforeach
        </ul>
    </div>
</div>


@endsection