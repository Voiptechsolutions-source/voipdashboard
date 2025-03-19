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

<script>
    $(document).ready(function() {

    $('#customerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.index') }}",
        columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'id', name: 'id' },
        { data: 'full_name', name: 'full_name' },
        { data: 'email', name: 'email' },
        { data: 'contact_no', name: 'contact_no' },
        { data: 'status', name: 'status' },
        { data: 'source', name: 'source' },
        { data: 'action', name: 'action', orderable: false, searchable: false},
        { data: 'view', name: 'view', orderable: false, searchable: false},  // New column
    ],
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    var table = $('#customerTable').DataTable();

    // Search by Name (Column Index 2)
    $('#nameSearch').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });

    // Search by Email (Column Index 5)
    $('#emailSearch').on('keyup', function () {
        table.column(5).search(this.value).draw();
    });

    // Search by Phone Number (Column Index 4)
    $('#phoneSearch').on('keyup', function () {
        table.column(4).search(this.value).draw();
    });

    // Filter by Status (Column Index 13)
    $('#statusFilter').on('change', function () {
        var status = this.value;
        if (status) {
            table.column(13).search(status).draw();
        } else {
            table.column(13).search("").draw();
        }
    });
    $(document).on('click', '.view-details', function() {
    var customerId = $(this).data('id');

    $.ajax({
        url: "/customers/" + customerId,  // Route to fetch customer details
        type: "GET",
        success: function(data) {
            $('#detailFullName').text(data.full_name);
            $('#detailEmail').text(data.email);
            $('#detailContact').text(data.contact_no);
            $('#detailAddress').text(data.address);
            $('#detailMessage').text(data.message);
            $('#detailDescription').text(data.description);

            $('#customerDetailModal').modal('show');  // Show modal
        }
    });
});

});
</script>
@endsection
