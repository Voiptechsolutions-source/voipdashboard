@extends('layouts.app')

@section('content')
<div class="container">
    <div class="pagetitle">
        <h1>Customers List</h1>
    </div>
    <section class="section">
    <div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
    <div class="table-responsive">
    <div class="container my-3">
    <div class="row g-2 align-items-center">
        <!-- Search by Name -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" id="nameSearch" class="form-control" placeholder="Search by Name">
            </div>
        </div>

        <!-- Search by Email -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="text" id="emailSearch" class="form-control" placeholder="Search by Email">
            </div>
        </div>

        <!-- Search by Phone -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" id="phoneSearch" class="form-control" placeholder="Search by Phone">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-funnel"></i></span>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Complete">Complete</option>
                    <option value="New Lead">New Lead</option>
                </select>
            </div>
        </div>
    </div>
</div>
     <table id="customerTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Status</th>
                <th>Source</th>
                <th>Action</th>
                <th>View Full Detail</th>  <!-- New Column -->
            </tr>
        </thead>
    </table>
    <!-- Full Detail Modal -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailModalLabel">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Full Name:</strong> <span id="detailFullName"></span></p>
                <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                <p><strong>Contact:</strong> <span id="detailContact"></span></p>
                <p><strong>Address:</strong> <span id="detailAddress"></span></p>
                <p><strong>Message:</strong> <span id="detailMessage"></span></p>
                <p><strong>Description:</strong> <span id="detailDescription"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
</div>

@endsection
