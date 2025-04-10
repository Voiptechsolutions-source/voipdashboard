@extends('layouts.app')

@section('content')


<div class="container">
    <div class="pagetitle">
        <h1>{{ $isSuperAdmin ? 'Leads' : 'My Assigned Leads' }}</h1>
    </div>
    <section class="section">
    <div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
    <div class="table-responsive">
    <div class="container my-3">
    <div class="row g-2 align-items-center">
        <div class="d-flex justify-content-between mb-3">
        @if($isSuperAdmin)
            <a href="{{ route('leads.create') }}" class="btn btn-success">Add New Lead</a>
        @endif
    <div id="dt-exports"></div>
</div>
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
                <th>Date</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Country Code</th>
                <th>Contact No</th>
                <th>Status</th>
                <th>Source</th>
                <th>Service</th>
                <th>Service Type</th>
                <th>Assigned to</th>
                <th>Assigned Username</th>
                
                <th>Actions</th> <!-- Single Actions column -->
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
        <p><strong>Country Code:</strong> <span id="detailcountrycode"></span></p>
        <p><strong>Contact No:</strong> <span id="detailContact"></span></p>
        <p><strong>Address:</strong> <span id="detailAddress"></span></p>
        <p><strong>PinCode:</strong> <span id="detailpincode"></span></p>
        <p><strong>Message:</strong> <span id="detailMessage"></span></p>
        <p><strong>Status Description:</strong> <span id="detailDescription"></span></p>
        <p><strong>Service Name:</strong> <span id="detailService"></span></p>
        <p><strong>Service Type:</strong> <span id="detailServicetype"></span></p>
        <p><strong>Industry:</strong> <span id="detailindustry"></span></p>
        <p><strong>Number of Users:</strong> <span id="detailUsers"></span></p>
        <p><strong>Comment:</strong> <span id="detailComment"></span></p>
        <p><strong>Customer Description:</strong> <span id="detailCustomerDesc"></span></p>
        <p><strong>Lead ID:</strong> <span id="detailLeadID"></span></p>
        <p><strong>Campaign ID:</strong> <span id="detailCampaignID"></span></p>
        <p><strong>Form ID:</strong> <span id="detailFormID"></span></p>
        <p><strong>Source:</strong> <span id="detailSource"></span></p>
        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
        <!-- <p><strong>Converted Lead:</strong> <span id="detailConvertedLead"></span></p> -->
      </div>
    </div>
  </div>
</div>

<!---- status Modal --->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content style="width: 122%;">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateStatusForm">
          <input type="hidden" id="rowId" name="id">
          
          <!-- Label to display the ID -->
          <div class="mb-3">
            <label for="data" class="form-label">Lead ID</label>
            <label id="data" class="form-control"></label>
          </div>
          
          <!-- Lead History -->
        <div id="leads-history-section" style="display: none;">
            <div class="pagetitle">
                <h5>Lead History</h5>
            </div>
            <section class="section" style="height: 300px; overflow-y: auto;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive1">
                                    <table id="leadshistoryTable" class="table table-striped">
                                    <thead>
                                            <tr style="position: sticky;top: 0;">
                                                <th>Status</th>
                                                <th>Description/Comment</th>
                                                <th>Added By</th>
                                                <th>Added At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
          <!-- Description in read-only text area -->
          <div class="mb-3">
            <input type="hidden" id="statusHidden" name="status"> <!-- Hidden field for status -->
            <label for="description" class="form-label">Description/Comment</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
          </div>

          <!-- Dropdown to update the status -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="0">Pending</option>
              <option value="1">Complete</option>
              <option value="2">New Lead</option>
            </select>
          </div>
          <input type="hidden" id="csrf-token" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!---Ending status Modal--->

<!-- Edit Customer Modal -->
<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <input type="hidden" id="editCustomerId" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" id="editFullName" name="full_name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Country Code</label>
                                <input type="text" class="form-control" id="editCountryCode" name="country_code">
                            </div>
                            <div class="form-group">
                                <label>Contact No</label>
                                <input type="text" class="form-control" id="editContactNo" name="contact_no">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" id="editAddress" name="address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Pincode</label>
                                <input type="text" class="form-control" id="editPincode" name="pincode">
                            </div>
                            <div class="form-group">
                                <label>Status Description</label>
                                <textarea class="form-control" id="editstatusdesc" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Customer Description</label>
                                <textarea class="form-control" id="editcustomerdesc" name="customer_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" id="editComment" name="comment"></textarea>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" class="form-control" id="editServiceName" name="service_name">
                            </div>
                            <div class="form-group">
                                <label>Service Type</label>
                                <input type="text" class="form-control" id="editServiceType" name="service_type">
                            </div>
                             <div class="form-group">
                                <label>Industry</label>
                                <input type="text" class="form-control" id="editindustry" name="industry">
                            </div>
                            <div class="form-group">
                                <label>Number of Users</label>
                                <input type="text" class="form-control" id="editNumberOfUsers" name="number_of_users">
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" id="editMessage" name="message"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Source</label>
                               <input type="text" class="form-control" id="editsource" name="source">
                            </div>
                            <div class="form-group">
                                <label>campaign Id</label>
                               <input type="text" class="form-control" id="editcampaign" name="campaign_id">
                            </div>
                            <div class="form-group">
                                <label>Form Id</label>
                               <input type="text" class="form-control" id="editformid" name="form_id">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="editStatus" name="status">
                                    <option value="0">Pending</option>
                                    <option value="1">Complete</option>
                                    <option value="2">New Lead</option>
                                </select>
                            </div>
                            

                        </div>

                    </div>
                    
                    <button type="submit" class="btn btn-success">Update Customer</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!----assign modal--->

<div class="modal fade" id="assignLeadModal" tabindex="-1" aria-labelledby="assignLeadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignLeadModalLabel">Assign Lead</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="assignLeadForm">
                            <input type="hidden" id="assignLeadId" name="lead_id">
                            <div class="mb-3">
                                <label for="assignUser" class="form-label">Assign to User</label>
                                <select class="form-select" id="assignUser" name="user_id" required>
                                    <option value="">Select a User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<!-- Pass permissions to JavaScript -->
<script>
   
    window.canView = {{ $canView ? 'true' : 'false' }};
    window.canEdit = {{ $canEdit ? 'true' : 'false' }};
    window.canDelete = {{ $canDelete ? 'true' : 'false' }};
   window.isSuperAdmin = {{ $isSuperAdmin ? 'true' : 'false' }};
</script>
<style>
   /*.sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 20px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }*/
    .sidebar-sticky .nav-link {
        color: #333;
    }
    .sidebar-sticky .nav-link.active {
        background-color: #007bff;
        color: white;
    }
    .table thead th {
        background-color: #343a40;
        color: white;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .btn-group button {
        margin: 0 5px;
        display: inline-block;
    }
    #editCustomerModal button {
        display: inline-block;
        float: left;
        margin-top: 1em;
    }
    .input-group-text {
        background-color: #fff;
        border-right: none;
    }
    .input-group .form-control {
        border-left: none;
    }
    div#customerTable_length {
        margin-bottom: 20px;
    }

</style>
@endsection
