@extends('layouts.app')

@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-12">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('danger'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('danger') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Customer Management</h4>

                    <ul class="nav nav-tabs mb-3" id="customer-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="true">
                                Upload Customer
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">
                                List Customer
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="customer-tab-content">
                        <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab" tabindex="0">
                            <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                {{ csrf_field() }}

                                <div class="col-lg-8">
                                    <label for="customer_file" class="form-label">Select File</label>
                                    <input type="file" id="customer_file" name="customer" class="form-control" accept=".xls,.xlsx,.csv" required>
                                    <small class="text-muted">Accepted formats: .xls, .xlsx, .csv</small>
                                    @if ($errors->has('customer'))
                                        <div class="text-danger mt-1">{{ $errors->first('customer') }}</div>
                                    @endif
                </div>

                                <div class="col-lg-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-warning">Upload</button>
                                </div>
                            </form>

                            <div class="mt-3 text-muted">
                                <strong>CSV Format:</strong> customer_code | customer_name
                            </div>
                        </div>

                        <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ route('customer.create') }}" class="btn btn-success">Add New Customer</a>
                            </div>

                                @if($data->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th width="5%">ID</th>
                                                <th>Customer Code</th>
                                                <th>Customer Name</th>
                                                <th width="15%">Created Date</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $customer)
                                                <tr>
                                                    <td>{{ $customer->id }}</td>
                                                    <td>{{ $customer->customer_code }}</td>
                                                    <td>{{ $customer->customer_name }}</td>
                                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="{{ route('customer.delete', $customer->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-info">
                                        <strong>No customers found.</strong> <a href="{{ route('customer.create') }}">Create one now</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
