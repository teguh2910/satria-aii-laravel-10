@extends('layouts.app')

@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-md-12">
            <!-- Alert Messages -->
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Success!</strong> {{ session('message') }}
                </div>
            @endif

            @if (session('danger'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Error!</strong> {{ session('danger') }}
                </div>
            @endif

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Customer Management</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#upload" aria-controls="upload" role="tab" data-toggle="tab">
                                <i class="fa fa-upload"></i> Upload Customer
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#list" aria-controls="list" role="tab" data-toggle="tab">
                                <i class="fa fa-list"></i> List Customer
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Upload Tab -->
                        <div role="tabpanel" class="tab-pane active" id="upload">
                            <div class="upload-section">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label for="customer_file" class="col-sm-3 control-label">Select File</label>
                                                <div class="col-sm-9">
                                                    <input type="file" id="customer_file" name="customer" class="form-control" accept=".xls,.xlsx,.csv" required>
                                                    <small class="form-text text-muted">Accepted formats: .xls, .xlsx, .csv</small>
                                                    @if ($errors->has('customer'))
                                                        <span class="text-danger"><i class="fa fa-warning"></i> {{ $errors->first('customer') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-warning btn-lg">
                                                        <i class="fa fa-cloud-upload"></i> Upload
                                                    </button>
                                                    <p class="help-block mt-15">
                                                        <strong>CSV Format:</strong> customer_code | customer_name
                                                    </p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- List Tab -->
                        <div role="tabpanel" class="tab-pane" id="list">
                            <div class="list-section">
                                <div class="row margin-bottom">
                                    <div class="col-md-12">
                                        <a href="{{ route('customer.create') }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> Add New Customer
                                        </a>
                                    </div>
                                </div>

                                @if($data->count() > 0)
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr class="bg-primary">
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
                                                        <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning btn-xs">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <a href="{{ route('customer.delete', $customer->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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

<style>
    .upload-section {
        padding: 30px 0;
    }

    .list-section {
        padding: 20px 0;
    }

    .margin-bottom {
        margin-bottom: 20px;
    }

    .mt-15 {
        margin-top: 15px;
    }

    .bg-primary {
        background-color: #0097bc !important;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .nav-tabs {
        border-bottom: 2px solid #0097bc;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        border-radius: 4px 4px 0 0;
        border: 1px solid #0097bc;
        border-bottom-color: white;
        background-color: #f5f5f5;
        color: #0097bc;
    }
</style>

@endsection
