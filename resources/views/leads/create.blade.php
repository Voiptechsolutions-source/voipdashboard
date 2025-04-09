@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Lead</h1>

    <form method="POST" action="{{ route('leads.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="full_name">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="country_code">Country Code</label>
                    <input type="text" class="form-control @error('country_code') is-invalid @enderror" id="country_code" name="country_code" value="{{ old('country_code') }}">
                    @error('country_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact_no">Contact No</label>
                    <input type="text" class="form-control @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" value="{{ old('contact_no') }}">
                    @error('contact_no')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}">
                    @error('pincode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="service_name">Service Name</label>
                    <input type="text" class="form-control @error('service_name') is-invalid @enderror" id="service_name" name="service_name" value="{{ old('service_name') }}">
                    @error('service_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="service_type">Service Type</label>
                    <input type="text" class="form-control @error('service_type') is-invalid @enderror" id="service_type" name="service_type" value="{{ old('service_type') }}">
                    @error('service_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" class="form-control @error('industry') is-invalid @enderror" id="industry" name="industry" value="{{ old('industry') }}">
                    @error('industry')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="number_of_users">Number of Users</label>
                    <input type="text" class="form-control @error('number_of_users') is-invalid @enderror" id="number_of_users" name="number_of_users" value="{{ old('number_of_users') }}">
                    @error('number_of_users')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="source">Source</label>
                    <select class="form-control @error('source') is-invalid @enderror" id="source" name="source">
                        <option value="">Select Source</option>
            <option value="Google" {{ old('source') == 'Google' ? 'selected' : '' }}>Google</option>
            <option value="Facebook" {{ old('source') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
            <option value="CSV" {{ old('source') == 'CSV' ? 'selected' : '' }}>CSV</option>
            <option value="Manual" {{ old('source') == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                    @error('source')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="2" {{ old('status', '2') == '2' ? 'selected' : '' }}>New Lead</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Complete</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message">{{ old('message') }}</textarea>
                    @error('message')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment">{{ old('comment') }}</textarea>
                    @error('comment')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customer_description">Customer Description</label>
                    <textarea class="form-control @error('customer_description') is-invalid @enderror" id="customer_description" name="customer_description">{{ old('customer_description') }}</textarea>
                    @error('customer_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="lead_id">Lead ID</label>
                    <input type="text" class="form-control @error('lead_id') is-invalid @enderror" id="lead_id" name="lead_id" value="{{ old('lead_id') }}">
                    @error('lead_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="campaign_id">Campaign ID</label>
                    <input type="number" class="form-control @error('campaign_id') is-invalid @enderror" id="campaign_id" name="campaign_id" value="{{ old('campaign_id') }}">
                    @error('campaign_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="form_id">Form ID</label>
                    <input type="number" class="form-control @error('form_id') is-invalid @enderror" id="form_id" name="form_id" value="{{ old('form_id') }}">
                    @error('form_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Lead</button>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection