@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Send Email Manually</h1>
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
    <form action="{{ route('send-email') }}" method="POST">
        @csrf
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
            <label for="lead_ids" class="form-label">Select Customers</label>
            <select name="lead_ids[]" id="lead_ids" class="form-control" multiple="multiple" style="width: 100%;">
                @foreach ($leads as $lead)
                    <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->email }})</option>
                @endforeach
            </select>
            <small class="text-muted">Search and select multiple customers. Type to filter.</small>
        </div>
        <button type="submit" class="btn btn-primary">Send Emails</button>
    </form>
</div>
@endsection

@section('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lead_ids').select2({
                placeholder: "Search for customers...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection