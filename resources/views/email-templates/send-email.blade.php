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
            <label for="lead_ids" class="form-label">Select Leads (Lead Status = 1)</label>
            <select name="lead_ids[]" id="lead_ids" class="form-control" multiple="multiple" style="width: 100%;">
                @foreach ($leads as $lead)
                    <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->email }})</option>
                @endforeach
            </select>
            <small class="text-muted">Search and select multiple leads. Hold Ctrl (or Cmd) to select multiple.</small>
        </div>
        <button type="submit" class="btn btn-primary">Send Emails</button>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#lead_ids').select2({
            placeholder: "Search for leads...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
@endsection