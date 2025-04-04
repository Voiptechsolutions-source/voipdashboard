@extends('layouts.app')

@section('content')
  <div class="container">
    <h2>No Access</h2>
    <p>You do not have permission to view this page.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
  </div>
@endsection