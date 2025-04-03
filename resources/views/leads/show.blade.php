@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Customer Details</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $customer->full_name }}</h5>
                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                        <p><strong>Contact Number:</strong> {{ $customer->contact_number }}</p>
                        <p><strong>Country Code:</strong> {{ $customer->country_code }}</p>
                        <p><strong>Followback Status:</strong> {{ $customer->followback_status ? 'Active' : 'Inactive' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
