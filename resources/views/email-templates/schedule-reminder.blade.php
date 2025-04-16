@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Schedule Reminder</h1>
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
    <form action="{{ route('schedule-reminder') }}" method="POST">
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
            <label for="recipient_email" class="form-label">Recipient Email</label>
            <input type="email" name="recipient_email" id="recipient_email" class="form-control" value="{{ old('recipient_email') }}" required>
        </div>
        <div class="mb-3">
            <label for="reminder_delays" class="form-label">Reminder Delays (in hours, one per line)</label>
            <textarea name="reminder_delays" id="reminder_delays" class="form-control" rows="3" required>{{ old('reminder_delays', "2\n3") }}</textarea>
            <small class="text-muted">Enter delay times in hours (e.g., 2, 3) one per line.</small>
        </div>
        <button type="submit" class="btn btn-primary">Schedule Reminders</button>
    </form>
</div>
@endsection