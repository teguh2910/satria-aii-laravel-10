@extends('layouts.app')

@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i> Add New Customer</h3>
                </div>
                <form class="form-horizontal" method="POST" action="{{ route('customer.store.form') }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="customer_code" class="col-sm-2 control-label">Customer Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_code" name="customer_code" placeholder="Enter customer code" required autofocus>
                                @if ($errors->has('customer_code'))
                                    <span class="text-danger">{{ $errors->first('customer_code') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_name" class="col-sm-2 control-label">Customer Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name" required>
                                @if ($errors->has('customer_name'))
                                    <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{ route('customer.index') }}" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success pull-right">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
