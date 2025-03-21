$(document).ready(function() {

    $('#customerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: customersIndexUrl,
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