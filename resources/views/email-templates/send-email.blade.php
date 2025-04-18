@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Send Email</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <ul class="nav nav-tabs" id="emailTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="add-groups-tab" data-bs-toggle="tab" data-bs-target="#add-groups" type="button" role="tab" aria-controls="add-groups" aria-selected="true">Add Groups</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="send-group-email-tab" data-bs-toggle="tab" data-bs-target="#send-group-email" type="button" role="tab" aria-controls="send-group-email" aria-selected="false">Send Group Email</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="compose-email-tab" data-bs-toggle="tab" data-bs-target="#compose-email" type="button" role="tab" aria-controls="compose-email" aria-selected="false">Compose Email</button>
        </li>
    </ul>

    <div class="tab-content" id="emailTabsContent">
        <!-- Add Groups Tab -->
        <div class="tab-pane fade show active" id="add-groups" role="tabpanel" aria-labelledby="add-groups-tab">
            <form action="{{ route('store-group') }}" method="POST" class="mt-3" id="create-group-form">
                @csrf
                <input type="hidden" name="tab" value="add-groups">
                <div class="mb-3">
                    <label for="group_name" class="form-label">Group Name</label>
                    <input type="text" name="group_name" id="group_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="emails" class="form-label">Select Leads or Add Custom Emails</label>
                    <select name="emails[]" id="emails" class="form-control" multiple="multiple" style="width: 100%;">
                        @foreach ($leads as $lead)
                            <option value="{{ $lead->email }}">{{ $lead->name }} ({{ $lead->email }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Search for leads or type custom emails (e.g., test@gmail.com) to add. Use commas to separate multiple entries.</small>
                </div>
                <button type="submit" class="btn btn-primary">Create Group</button>
            </form>
            <h3 class="mt-4">Existing Groups</h3>
            @forelse ($groups as $group)
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title">{{ $group->name }}</h5>
                        <p class="card-text">Emails: {{ implode(', ', $group->emails) }}</p>
                        <form action="{{ route('update-group', $group) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-info btn-sm edit-group" data-id="{{ $group->id }}" data-emails="{{ implode(',', $group->emails) }}">Edit</button>
                        </form>
                        <form action="{{ route('delete-group', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No groups created yet.</p>
            @endforelse
        </div>

        <!-- Send Group Email Tab -->
        <!-- Send Group Email Tab -->
<div class="tab-pane fade" id="send-group-email" role="tabpanel" aria-labelledby="send-group-email-tab">
    <form action="{{ route('send-email.post') }}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" name="tab" value="send-group-email">
        <div class="mb-3">
            <label for="template_id" class="form-label">Select Template</label>
            <select name="template_id" id="template_id" class="form-control" required>
                <option value="">Select a template</option>
                @foreach ($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="group_id" class="form-label">Select Group</label>
            <select name="group_id" id="group_id" class="form-control" required>
                <option value="">Select a group</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }} ({{ implode(', ', $group->emails) }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Send Emails</button>
    </form>
</div>

        <!-- Compose Email Tab -->
        <div class="tab-pane fade" id="compose-email" role="tabpanel" aria-labelledby="compose-email-tab">
            <form action="{{ route('send-email.post') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="tab" value="compose-email">
                <div class="mb-3">
                    <label for="to_email" class="form-label">To (Comma-separated emails)</label>
                    <input type="text" name="to_email" id="to_email" class="form-control" placeholder="e.g., test1@gmail.com, test2@gmail.com" required>
                    <small class="text-muted">Enter multiple emails separated by commas.</small>
                </div>
                <div class="mb-3">
                    <label for="custom_subject" class="form-label">Subject</label>
                    <input type="text" name="custom_subject" id="custom_subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="custom_body" class="form-label">Body</label>
                    <textarea name="custom_body" id="custom_body" class="form-control" rows="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- TinyMCE CSS -->
    <style>
        .edit-group-form { display: none; }
    </style>
@endsection

@section('scripts')
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TinyMCE JS -->
    <script src="https://cdn.tiny.cloud/1/eecv9gurjuhytimxqezzv5tmpqayd32dlllxat3pl0g023mi/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for creating new groups
            $('#emails').select2({
                placeholder: "Search for leads or add custom emails...",
                allowClear: true,
                tags: true,
                width: '100%',
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(term)) {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true
                    };
                },
                templateResult: function(data) {
                    if (data.newTag) {
                        return $('<span class="badge bg-info text-dark">Custom: ' + data.text + '</span>');
                    }
                    return data.text;
                },
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    if (data.newTag) {
                        return data.text.toUpperCase().indexOf(params.term.toUpperCase()) === 0 ? data : null;
                    }
                    return data.text.toUpperCase().indexOf(params.term.toUpperCase()) >= 0 ? data : null;
                }
            });

            // Handle edit group functionality
            $('.edit-group').click(function() {
                var id = $(this).data('id');
                var emails = $(this).data('emails').split(',').map(email => email.trim());
                var $form = $('<form action="{{ route("update-group", ":id") }}" method="POST" class="edit-group-form mt-2">')
                    .attr('action', '{{ route("update-group", ":id") }}'.replace(':id', id))
                    .append('@csrf @method("PATCH")')
                    .append('<div class="mb-3"><label for="emails_' + id + '_input" class="form-label">Emails</label><select name="emails[]" id="emails_' + id + '_input" multiple style="width: 100%;"></select></div>')
                    .append('<button type="submit" class="btn btn-primary btn-sm">Save</button>')
                    .append('<button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancel</button>');
                $(this).closest('.card-body').append($form);
                $form.show();
                $(this).hide();

                // Initialize Select2 for the edit form
                $('#emails_' + id + '_input').select2({
                    tags: true,
                    multiple: true,
                    width: '100%',
                    data: emails.map(email => ({ id: email, text: email })),
                    createTag: function(params) {
                        var term = $.trim(params.term);
                        if (term === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(term)) {
                            return null;
                        }
                        return { id: term, text: term, newTag: true };
                    },
                    templateResult: function(data) {
                        if (data.newTag) {
                            return $('<span class="badge bg-info text-dark">Custom: ' + data.text + '</span>');
                        }
                        return data.text;
                    }
                }).val(emails).trigger('change');
            });

            $(document).on('click', '.cancel-edit', function() {
                $(this).closest('.edit-group-form').remove();
                $('.edit-group').show();
            });

            $('#create-group-form').on('submit', function(e) {
                var emails = $('#emails').val() || [];
                $('#emails').val(emails); // Ensure the select value is set
            });

            tinymce.init({
                selector: '#custom_body',
                height: 300,
                plugins: 'advlist autolink lists link image charmap print preview anchor',
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });

            function loadTemplate() {
                var templateId = $('#template_id').val(); // Fixed to match the Send Group Email tab's select
                console.log('Selected template ID:', templateId); // Replace alert with console.log for better debugging
                if (templateId) {
                    $.ajax({
                        url: '/get-template/' + templateId,
                        method: 'GET',
                        success: function(response) {
                            $('#custom_subject').val(response.subject);
                            tinymce.get('custom_body').setContent(response.body);
                            if (!response.is_active) {
                                alert('Warning: This template is inactive.');
                            }
                        },
                        error: function(xhr) {
                            console.error('Failed to load template:', xhr.responseJSON?.error || 'Unknown error');
                            $('#custom_subject').val('');
                            tinymce.get('custom_body').setContent('');
                        }
                    });
                } else {
                    $('#custom_subject').val('');
                    tinymce.get('custom_body').setContent('');
                }
            }

            // Call loadTemplate when the template dropdown changes
            $('#template_id').on('change', loadTemplate);
            loadTemplate(); // Initial load
        });

    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 1000); // 1000 milliseconds = 1 second
        }
    });

    </script>
@endsection