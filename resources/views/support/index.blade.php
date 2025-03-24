@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pagetitle">
            <h1>Support Revenue</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="supportTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Contact No</th>
                                            <th>Notes</th>
                                            <th>Revenue Per Day ($)</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supportData as $support)
                                            <tr>
                                                <td>{{ $support->id }}</td>
                                                <td>{{ $support->full_name }}</td>
                                                <td>{{ $support->email }}</td>
                                                <td>{{ $support->contact_no }}</td>
                                                <td>{{ $support->notes }}</td>
                                                <td>${{ $support->revenue_per_day }}</td>
                                                <td>{{ $support->created_at }}</td>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#supportTable').DataTable(); // Initialize DataTable

        
    });
</script>
@endsection
