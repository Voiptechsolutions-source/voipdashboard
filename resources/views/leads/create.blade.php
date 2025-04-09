@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Create New Lead</h2>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('leads.store') }}" enctype="application/x-www-form-urlencoded" onsubmit="return validateForm(event)">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country_code" class="form-label">Country Code <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-row number-select">
                                        <select class="form-control country-code" id="country_code" name="country_code" style="width: 90px;" required>
                                            <option value="">Select Country Code</option>
                                            <option value="+91" {{ old('country_code', '+91') == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                                            <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                            <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇨🇦 +1</option>
                                            <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>🇦🇪 +971</option>
                                            <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                            <option value="+61" {{ old('country_code') == '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                                            <option value="+63" {{ old('country_code') == '+63' ? 'selected' : '' }}>🇵🇭 +63</option>
                                            <option value="+92" {{ old('country_code') == '+92' ? 'selected' : '' }}>🇵🇰 +92</option>
                                            <option value="+880" {{ old('country_code') == '+880' ? 'selected' : '' }}>🇧🇩 +880</option>
                                            <option value="+966" {{ old('country_code') == '+966' ? 'selected' : '' }}>🇸🇦 +966</option>
                                            <option value="+968" {{ old('country_code') == '+968' ? 'selected' : '' }}>🇴🇲 +968</option>
                                            <option value="+973" {{ old('country_code') == '+973' ? 'selected' : '' }}>🇧🇭 +973</option>
                                            <option value="+977" {{ old('country_code') == '+977' ? 'selected' : '' }}>🇳🇵 +977</option>
                                            <option value="+49" {{ old('country_code') == '+49' ? 'selected' : '' }}>🇩🇪 +49</option>
                                            <option value="+33" {{ old('country_code') == '+33' ? 'selected' : '' }}>🇫🇷 +33</option>
                                            <option value="+34" {{ old('country_code') == '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                                            <option value="+39" {{ old('country_code') == '+39' ? 'selected' : '' }}>🇮🇹 +39</option>
                                            <option value="+55" {{ old('country_code') == '+55' ? 'selected' : '' }}>🇧🇷 +55</option>
                                            <option value="+54" {{ old('country_code') == '+54' ? 'selected' : '' }}>🇦🇷 +54</option>
                                            <option value="+48" {{ old('country_code') == '+48' ? 'selected' : '' }}>🇵🇱 +48</option>
                                            <option value="+27" {{ old('country_code') == '+27' ? 'selected' : '' }}>🇿🇦 +27</option>
                                            <option value="+52" {{ old('country_code') == '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                                            <option value="+65" {{ old('country_code') == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                                            <option value="+40" {{ old('country_code') == '+40' ? 'selected' : '' }}>🇷🇴 +40</option>
                                            <option value="+216" {{ old('country_code') == '+216' ? 'selected' : '' }}>🇹🇳 +216</option>
                                            <option value="+57" {{ old('country_code') == '+57' ? 'selected' : '' }}>🇨🇴 +57</option>
                                        </select>
                                        <i class="angle-down country-arrow"><img src="{{ asset('images/arrow-icon.png') }}" alt=""></i>
                                        <input type="tel" name="contact_no" placeholder="WhatsApp Number/ Phone Number" id="contact_no" class="form-control" maxlength="12" required>
                                    </div>
                                    @error('country_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    @error('contact_no')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="industry" class="form-label">Industry <span class="text-danger">*</span></label>
                                    <select class="form-select @error('industry') is-invalid @enderror" id="industry" name="industry" required>
                                        <option value="">Select Your Industry</option>
                                        <option value="Retail and E-commerce" {{ old('industry') == 'Retail and E-commerce' ? 'selected' : '' }}>Retail and E-commerce</option>
                                        <option value="Healthcare" {{ old('industry') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                        <option value="Education" {{ old('industry') == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Banking and Finance" {{ old('industry') == 'Banking and Finance' ? 'selected' : '' }}>Banking and Finance</option>
                                        <option value="Real Estate" {{ old('industry') == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                        <option value="Travel and Tourism" {{ old('industry') == 'Travel and Tourism' ? 'selected' : '' }}>Travel and Tourism</option>
                                        <option value="Event Management" {{ old('industry') == 'Event Management' ? 'selected' : '' }}>Event Management</option>
                                        <option value="Telecommunications" {{ old('industry') == 'Telecommunications' ? 'selected' : '' }}>Telecommunications</option>
                                        <option value="Government and Public Services" {{ old('industry') == 'Government and Public Services' ? 'selected' : '' }}>Government and Public Services</option>
                                        <option value="Non-profit and Charities" {{ old('industry') == 'Non-profit and Charities' ? 'selected' : '' }}>Non-profit and Charities</option>
                                        <option value="Hospitality and Food Services" {{ old('industry') == 'Hospitality and Food Services' ? 'selected' : '' }}>Hospitality and Food Services</option>
                                        <option value="Transportation and Logistics" {{ old('industry') == 'Transportation and Logistics' ? 'selected' : '' }}>Transportation and Logistics</option>
                                        <option value="Media and Entertainment" {{ old('industry') == 'Media and Entertainment' ? 'selected' : '' }}>Media and Entertainment</option>
                                        <option value="Production and Manufacturing" {{ old('industry') == 'Production and Manufacturing' ? 'selected' : '' }}>Production and Manufacturing</option>
                                        <option value="Energy Utility and Waste" {{ old('industry') == 'Energy Utility and Waste' ? 'selected' : '' }}>Energy Utility and Waste</option>
                                        <option value="Others" {{ old('industry') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('industry')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="services" class="form-label">Service Name <span class="text-danger">*</span></label>
                                    <select class="form-select @error('services') is-invalid @enderror" id="services" name="service_name" required>
                                        <option value="">Select Service</option>
                                        <option value="marketing sms" {{ old('services') == 'marketing sms' ? 'selected' : '' }}>Marketing SMS</option>
                                        <option value="bulk sms" {{ old('services') == 'bulk sms' ? 'selected' : '' }}>Bulk SMS</option>
                                        <option value="otp service sms" {{ old('services') == 'otp service sms' ? 'selected' : '' }}>OTP Services SMS</option>
                                        <option value="transactional sms" {{ old('services') == 'transactional sms' ? 'selected' : '' }}>Transactional SMS</option>
                                        <option value="promotional sms" {{ old('services') == 'promotional sms' ? 'selected' : '' }}>Promotional SMS</option>
                                        <option value="Others" {{ old('services') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('services')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('service_type') is-invalid @enderror" id="service_type" name="service_type" value="{{ old('service_type') }}" required>
                                    @error('service_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}" required>
                                    @error('pincode')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number_of_users" class="form-label">Number of Users <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('number_of_users') is-invalid @enderror" id="number_of_users" name="number_of_users" value="{{ old('number_of_users') }}" required>
                                    @error('number_of_users')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                                    <select class="form-control @error('source') is-invalid @enderror" id="source" name="source" required>
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
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Complete</option>
                                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>New Lead</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_description" class="form-label">Customer Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('customer_description') is-invalid @enderror" id="customer_description" name="customer_description" rows="3" required>{{ old('customer_description') }}</textarea>
                                    @error('customer_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 mt-4 text-center"> <!-- Centered button with spacing -->
                                <button type="submit" class="btn btn-primary btn-lg">Create Lead</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateContactNumber(event) {
        const input = event.target;
        let value = input.value.replace(/[^0-9]/g, ''); // Allow only numbers
        if (value.length > 12) {
            value = value.slice(0, 12); // Limit to 12 digits
        }
        input.value = value;

        // Real-time feedback
        if (value.length === 0) {
            input.classList.remove('is-invalid');
        } else if (!/^\d{10,12}$/.test(value)) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    function validateForm(event) {
        let isValid = true;
        const contactNo = document.getElementById('contact_no').value;

        // Validate contact number (10-12 digits)
        if (contactNo.length > 0 && !/^\d{10,12}$/.test(contactNo)) {
            document.getElementById('contact_no').classList.add('is-invalid');
            isValid = false;
        } else {
            document.getElementById('contact_no').classList.remove('is-invalid');
        }

        // Validate email format
        const email = document.getElementById('email').value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            document.getElementById('email').classList.add('is-invalid');
            isValid = false;
        } else {
            document.getElementById('email').classList.remove('is-invalid');
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if invalid
            alert('Please correct the errors before submitting.');
        }
        return isValid;
    }
</script>
@endsection