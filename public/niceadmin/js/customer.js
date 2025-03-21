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
        { data: 'service_name', name: 'service_name' },
        { data: 'ConvertLead', name: 'action', orderable: false, searchable: false},
        { data: 'view', name: 'view', orderable: false, searchable: false},
        { data: 'Edit', name: 'view', orderable: false, searchable: false},
        { data: 'Delete', name: 'view', orderable: false, searchable: false},  // New column
    ],
        order: [[1, 'desc']],
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            emptyTable: "No data available in table",
            processing: "<span class='spinner-border spinner-border-sm'></span> Loading..."
        }
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
    //Full Detail Modal
    $(document).on('click', '.view-details', function() {
        var customerId = $(this).data('id');

        $.ajax({
            url: "/customers/" + customerId,  // Route to fetch customer details
            type: "GET",
            success: function(data) {
                function getStatusButton(status) {
                    switch (status) {
                        case '0': return '<button class="btn btn-danger btn-sm">Pending</button>';
                        case '1': return '<button class="btn btn-success btn-sm">Complete</button>';
                        case '2': return '<button class="btn btn-primary btn-sm">New Lead</button>';
                        default: return '<button class="btn btn-secondary btn-sm">No data</button>';
                    }
                }

                $('#detailFullName').text(data.full_name ? data.full_name : 'No data available');
                $('#detailEmail').text(data.email ? data.email : 'No data available');
                $('#detailContact').text(data.contact_no ? data.contact_no : 'No data available');
                $('#detailAddress').text(data.address ? data.address : 'No data available');
                $('#detailMessage').text(data.message ? data.message : 'No data available');
                $('#detailDescription').text(data.description ? data.description : 'No data available');
                $('#detailService').text(data.service_name ? data.service_name : 'No data available');
                $('#detailUsers').text(data.number_of_users ? data.number_of_users : 'No data available');
                $('#detailComment').text(data.comment ? data.comment : 'No data available');
                $('#detailCustomerDesc').text(data.customer_description ? data.customer_description : 'No data available');
                $('#detailLeadID').text(data.lead_id ? data.lead_id : 'No data available');
                $('#detailCampaignID').text(data.campaign_id ? data.campaign_id : 'No data available');
                $('#detailFormID').text(data.form_id ? data.form_id : 'No data available');
                $('#detailSource').text(data.source ? data.source : 'No data available');
                
                $('#detailStatus').html(getStatusButton(data.status)); // Display status as a button
                //$('#detailConvertedLead').text(data.convertedlead ? data.convertedlead : 'No data available');

                $('#customerDetailModal').modal('show');  // Show modal
            }
        });
    });



    //Status Modal
    $(document).on('click', '.update-status', function() {
        var customerId = $(this).data('id');

        $.ajax({
            url: "/customers/" + customerId,  // Fetch customer details
            type: "GET",
            success: function(data) {
                console.log(data);
                $('#rowId').val(data.id);  // Set hidden input ID
                $('#data').text(data.id);  // Show Row ID
                $('#description').val(data.description); // Set description
                $('#status').val(data.status);  // Set selected status

                $('#statusModal').modal('show');  // Show modal
            },
            error: function() {
                alert("Failed to fetch customer details.");
            }
        });
    });


    // Handle form submission to update status
    $('#updateStatusForm').on('submit', function (e) {
        e.preventDefault(); // Prevent page reload

        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "/update-status", // Update route
            type: "POST", // Ensure POST request
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
            },
            success: function (response) {
                alert(response.message);
                $('#statusModal').modal('hide'); // Close modal
                location.reload(); // Reload page
            },
            error: function () {
                alert("Error updating status.");
            }
        });
    });

    //Delete Row
    $(document).on('click', '.delete-row', function() {
        let rowId = $(this).data('id');  // Get the row ID
        let row = $(this).closest('tr'); // Select the row to remove
        //let csrfToken = $('#csrf-token').val(); // Fetch CSRF token from modal

        if (confirm("Are you sure you want to delete this entry?")) {
            $.ajax({
                url: '/customers/' + rowId, // Adjust this route based on your Laravel routes
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message); // Show success message
                    $('#customerTable').DataTable().row(row).remove().draw(); // Remove row from DataTable
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseJSON.message);
                }
            });
        }
    });

    //Convert Lead
    $(document).on('click', '.convert-lead', function() {
        let leadId = $(this).data('id');

        if (confirm("Are you sure you want to convert this lead?")) {
            $.ajax({
                url: '/convert-lead',
                type: 'POST',
                data: { lead_id: leadId },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // CSRF Token
                success: function(response) {
                    alert(response.message);

                    let button = $('.convert-lead[data-id="' + leadId + '"]');
                    button.removeClass('btn-success').addClass('btn-secondary disabled').text('Already Converted');
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseJSON.message);
                }
            });
        }
    });

    // edit customer lead

    $(document).on('click', '.edit-lead', function() {
    let rowId = $(this).data('id');

    $.ajax({
        url: '/customers/' + rowId + '/edit',
        type: 'GET',
        success: function(response) {
            $('#editCustomerId').val(response.id);
            $('#editFullName').val(response.full_name);
            $('#editEmail').val(response.email);
            $('#editCountryCode').val(response.country_code);
            $('#editContactNo').val(response.contact_no);
            $('#editAddress').val(response.address);
            $('#editPincode').val(response.pincode);
            $('#editServiceName').val(response.service_name);
            $('#editNumberOfUsers').val(response.number_of_users);
            $('#editMessage').val(response.message);
            $('#editComment').val(response.comment);
            $('#editStatus').val(response.status);
            
            $('#editCustomerModal').modal('show'); // Open modal
        },
        error: function(xhr) {
            alert("Error fetching data!");
        }
    });
});

// Handle form submission
$('#editCustomerForm').on('submit', function(e) {
    e.preventDefault();
    
    let rowId = $('#editCustomerId').val();
    
    $.ajax({
        url: '/customers/' + rowId,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: $(this).serialize(),
        success: function(response) {
            alert(response.message);
            $('#editCustomerModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert("Update failed: " + (xhr.responseJSON.message || "Error"));
        }
    });
});

    // Handle form submission
$('#editCustomerForm').on('submit', function(e) {
    e.preventDefault();
    
    let rowId = $('#editCustomerId').val();
    
    $.ajax({
        url: '/customers/' + rowId,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: $(this).serialize(),
        success: function(response) {
            alert(response.message);
            $('#editCustomerModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert("Update failed: " + (xhr.responseJSON.message || "Error"));
        }
    });
});

});


//});