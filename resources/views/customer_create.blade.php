@extends('layouts.app')

@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-lg-7 col-md-9 mx-auto">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Add New Customer</h4>

                    <form method="POST" action="{{ route('customer.store.form') }}">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label for="customer_code" class="form-label">Customer Code</label>
                            <input type="text" class="form-control" id="customer_code" name="customer_code" placeholder="Enter customer code" required autofocus>
                            @if ($errors->has('customer_code'))
                                <div class="text-danger mt-1">{{ $errors->first('customer_code') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name" required>
                            @if ($errors->has('customer_name'))
                                <div class="text-danger mt-1">{{ $errors->first('customer_name') }}</div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
