@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="pagetitle text-center">
            <h1>Import Customer</h1>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="card-title text-center">Upload CSV File</h5>

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('import.customers') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="csv_file" class="form-label">Choose CSV File:</label>
                                    <input type="file" class="form-control" name="csv_file" id="csv_file" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Import CSV</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>


@endsection
