$(document).ready(function() {

    $('#customerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: leadsIndexUrl,
        columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'id', name: 'id' },
        { data: 'created_at', name: 'created_at' },
        { data: 'full_name', name: 'full_name' },
        { data: 'email', name: 'email' },
        { data: 'country_code', name: 'country_code' },
        { data: 'contact_no', name: 'contact_no' },
        { data: 'status', name: 'status' },
        { data: 'source', name: 'source' },
        { data: 'service_name', name: 'service_name' },
        { data: 'service_type', name: 'service_type' },
        //{ data: 'ConvertLead', name: 'action', orderable: false, searchable: false},
        { data: 'view', name: 'view', orderable: false, searchable: false},
        { data: 'Edit', name: 'view', orderable: false, searchable: false},
        { data: 'Delete', name: 'view', orderable: false, searchable: false},  // New column
    ],
        order: [[1, 'desc']],
        paging: true,
        lengthMenu: [100,200,300,400],
        pageLength: 100,
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

    // 🔥 Fix for Status Filtering (Column Index 7)
    $('#statusFilter').on('change', function () {
        var status = $(this).val(); 
        console.log("Filtering by Status:", status);
        table.column(7).search(status).draw();
    });
    //Full Detail Modal
    $(document).on('click', '.view-details', function() {
        var customerId = $(this).data('id');

        $.ajax({
            url: "/leads/" + customerId,  // Route to fetch customer details
            type: "GET",
            success: function(data) {
                console.log(data);
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
                $('#detailCountrycode').text(data.country_code ? data.country_code : 'No data available');
                $('#detailAddress').text(data.address ? data.address : 'No data available');
                $('#detailMessage').text(data.message ? data.message : 'No data available');
                $('#detailDescription').text(data.description ? data.description : 'No data available');
                $('#detailService').text(data.service_name ? data.service_name : 'No data available');
                $('#detailServicetype').text(data.service_type ? data.service_type : 'No data available');
                $('#detailUsers').text(data.number_of_users ? data.number_of_users : 'No data available');
                $('#detailComment').text(data.comment ? data.comment : 'No data available');
                $('#detailCustomerDesc').text(data.customer_description ? data.customer_description : 'No data available');
                $('#detailcreateddate').text(data.created_at ? data.created_at : 'No data available');
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
            url: "/leads/" + customerId,  // Fetch customer details
            type: "GET",
            success: function(data) {
                console.log("Fetched Data:", data);

                $('#rowId').val(data.id);  // Set hidden input ID
                $('#data').text(data.id);  // Show Row ID
                $('#description').val(data.description); // Set description

                // ✅ Correctly select the status in the dropdown
                $('#status').val(data.status).trigger('change');

                // ✅ Disable status dropdown if status is "Complete" (1), otherwise enable it
                if (data.status == "1") {
                    $('#status').prop('disabled', true);
                } else {
                    $('#status').prop('disabled', false);
                }

                $('#statusModal').modal('show');  // Show modal
            },
            error: function() {
                alert("Failed to fetch customer details.");
            }
        });
    });




    // Handle form submission to update status
    $('#updateStatusForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let customerId = $('#rowId').val();
        let newStatus = $('#status').val();

        console.log("Submitting Status Update for ID:", customerId);
        console.log("Selected Status:", newStatus);

        $.ajax({
            url: "/update-status/" + customerId,
            type: "POST",
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                console.log("Status Update Response:", response);
                alert(response.message);

                // ✅ Update button text & color
                let statusButton = $('.update-status[data-id="' + customerId + '"]');
                if (newStatus === "1") {
                    statusButton.text("Complete").removeClass('btn-danger btn-warning').addClass('btn-success');
                    $('#status').prop('disabled', true); // ✅ Disable dropdown for "Complete"
                } else if (newStatus === "2") {
                    statusButton.text("New Lead").removeClass('btn-danger btn-success').addClass('btn-warning');
                    $('#status').prop('disabled', false); // ✅ Enable dropdown
                } else {
                    statusButton.text("Pending").removeClass('btn-success btn-warning').addClass('btn-danger');
                    $('#status').prop('disabled', false); // ✅ Enable dropdown
                }

                $('#statusModal').modal('hide'); // ✅ Close modal after update
                $('#customerTable').DataTable().ajax.reload(null, false); // ✅ Reload DataTable without full refresh
            },
            error: function (xhr) {
                console.log("Status Update Error:", xhr);
                alert("Failed to update status: " + (xhr.responseJSON?.message || "Unknown error"));
            }
        });
    });


    //Delete Row
    $(document).on('click', '.delete-row', function() {
        let rowId = $(this).data('id');
        let row = $(this).closest('tr');
        let inputPassword = prompt("Enter your admin password to confirm:");

        if (!inputPassword) {
            alert("Password is required!");
            return;
        }

        if (confirm("Are you sure you want to delete this entry?")) {
            $.ajax({
                url: '/leads/' + rowId,
                type: 'DELETE',
                xhrFields: { withCredentials: true },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { password: inputPassword }, // Sending password to backend
                success: function(response) {
                    alert(response.message);
                    $('#customerTable').DataTable().row(row).remove().draw();
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
        url: '/leads/' + rowId + '/edit',
        type: 'GET',
        success: function(response) {
            console.log(response);
            $('#editCustomerId').val(response.id);
            $('#editFullName').val(response.full_name);
            $('#editEmail').val(response.email);
            $('#editCountryCode').val(response.country_code);
            $('#editContactNo').val(response.contact_no);
            $('#editAddress').val(response.address);
            $('#editPincode').val(response.pincode);
            $('#editServiceName').val(response.service_name);
            $('#editServiceType').val(response.service_type);
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
        url: '/leads/' + rowId,
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
        url: '/leads/' + rowId,
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

//Handle convert lead page

// Open modal and fill data
    $('.add-revenue-btn').click(function () {
        
        var leadId = $(this).data('lead-id');
        var fullName = $(this).data('lead-name');
        var email = $(this).data('lead-email');

        $('#leadIdInput').val(leadId);
        $('#leadFullName').val(fullName);
        $('#leadEmail').val(email);
    });

    // Submit revenue form via AJAX
    $('#addRevenueForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url:"/support/saverevenue", // Properly formatted route
            type: "POST",
            data: formData,
            success: function (response) {
                alert('Revenue added successfully!');
                $('#addRevenueModal').modal('hide');
                location.reload();
            },
            error: function (xhr) {
                alert('Error adding revenue. Please try again.');
            }
        });
    });

    

});


//});