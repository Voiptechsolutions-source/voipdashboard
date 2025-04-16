@extends('layouts.app')

@section('title', 'Edit Email Template')

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

        <form method="POST" action="{{ route('email-templates.update', $emailTemplate->id) }}">
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
                <input type="hidden" name="body" id="hiddenBody" value="{{ htmlspecialchars_decode($emailTemplate->body ?? '', ENT_QUOTES) }}">
                <textarea id="body" class="form-control" rows="10"></textarea>
                <small class="text-muted">Use placeholders like {username}, {email}, {date}</small>
                
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>

            <!-- <div class="mb-3 form-check">
                <input type="checkbox" name="send_after_2_hours" id="send_after_2_hours" class="form-check-input" {{ $emailTemplate->send_after_2_hours ? 'checked' : '' }}>
                <label class="form-check-label" for="send_after_2_hours">Send After 2 Hours</label>
            </div> -->

            <button type="submit" class="btn btn-primary">Update Template</button>
        </form>
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
                                    console.warn('No content in hiddenBody');
                                    editor.setContent('<p>No content available.</p>');
                                }
                            } else {
                                console.error('hiddenBody element not found');
                                editor.setContent('<p>hiddenBody not found.</p>');
                            }
                        });
                        editor.on('change', function () {
                            var content = editor.getContent({ format: 'raw' });
                            document.getElementById('hiddenBody').value = content;
                            console.log('Editor content updated:', content);
                        });
                    }
                });

                document.getElementById('insertPlaceholder').addEventListener('click', function () {
                    var select = document.getElementById('placeholderSelect');
                    var placeholder = select.value;
                    if (placeholder && tinymce.activeEditor) {
                        tinymce.activeEditor.execCommand('mceInsertContent', false, placeholder);
                        console.log('Inserted placeholder:', placeholder);
                    }
                });
            } else {
                console.error('TinyMCE is not loaded. Check the CDN or API key.');
            }
        });
    </script>
@endsection