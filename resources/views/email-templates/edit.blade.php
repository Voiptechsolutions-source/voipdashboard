@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Template</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        $templateDebug = isset($template) && $template instanceof \App\Models\EmailTemplate ? "ID: {$template->id}, Name: {$template->name}" : "Not set or invalid: " . var_export($template, true);
    @endphp
    
    @if (isset($template) && $template instanceof \App\Models\EmailTemplate)
        <form method="POST" action="{{ route('email-templates.update', ['template' => $template->id]) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $template->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $template->subject) }}" required>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Body</label>
                <input type="hidden" name="body" id="hiddenBody" value="{{ htmlspecialchars_decode($template->body ?? '', ENT_QUOTES) }}">
                <textarea name="body" id="body" class="form-control" rows="5" required>{{ old('body', $template->body) }}</textarea>
                <small class="text-muted">Use placeholders like {username}, {email}, {date}</small>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Active</label>
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    @else
        <p>Warning: Cannot render form due to invalid template data. Please contact support.</p>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#body',
                plugins: 'advlist autolink lists link image charmap print preview anchor code',
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code',
                height: 400,
                content_style: 'body { font-family: Arial, sans-serif; }',
                valid_elements: '*[*]',
                extended_valid_elements: '*[*]',
                setup: function (editor) {
                    editor.on('init', function () {
                        var hiddenBody = document.getElementById('hiddenBody');
                        if (hiddenBody) {
                            var initialContent = hiddenBody.value.trim();
                            console.log('HiddenBody value before init:', initialContent);
                            if (initialContent) {
                                editor.setContent(initialContent);
                                console.log('Editor initialized with:', editor.getContent());
                            } else {
                                console.warn('No content in hiddenBody, using textarea fallback');
                                editor.setContent(document.getElementById('body').value || '<p>No content available.</p>');
                            }
                        } else {
                            console.error('hiddenBody element not found');
                            editor.setContent(document.getElementById('body').value || '<p>hiddenBody not found.</p>');
                        }
                    });
                    editor.on('change', function () {
                        var content = editor.getContent({ format: 'raw' });
                        document.getElementById('hiddenBody').value = content;
                        console.log('Editor content updated:', content);
                    });
                }
            });
        } else {
            console.error('TinyMCE is not loaded. Check the CDN or API key.');
        }
    });
</script>
@endsection