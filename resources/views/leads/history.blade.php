@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pagetitle">
            <h1>Leads History</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="leadshistoryTable" class="table table-striped">
                                    <tbody>
                                        @foreach($leadshistory as $lead)
                                            <tr>
                                                <td>Leads status from {{ $lead->old_status }} to {{ $lead->new_status }} and comment from {{ $lead->old_comment }} to {{ $lead->new_comment }} have been changed by- {{ $lead->full_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
    <!-- Add Revenue Modal -->
<!-- Add Revenue Modal -->
<div class="modal fade" id="addRevenueModal" tabindex="-1" aria-labelledby="addRevenueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRevenueModalLabel">Add Revenue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRevenueForm">
                    @csrf
                    <input type="hidden" name="lead_id" id="leadIdInput">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="leadFullName" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="leadEmail" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Enter notes..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="revenueAmount" class="form-label">Revenue per Day ($)</label>
                        <input type="number" class="form-control" id="revenueAmount" name="revenue_per_day" required>
                    </div>

                    <button type="submit" class="btn btn-success">Save Revenue</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#leadshistoryTable').DataTable(); // Initialize DataTable

        
    });
</script>
@endsection
