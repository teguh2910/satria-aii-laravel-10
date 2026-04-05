@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Customer</h3>
                </div>
                <form class="form-horizontal" method="POST" action="{{asset('edit_customer/'.$data->id)}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="customer_code" class="col-sm-2 control-label">Customer Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_code" name="customer_code" value="{{ $data->customer_code }}" required>
                                @if ($errors->has('customer_code'))
                                    <span class="text-danger">{{ $errors->first('customer_code') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_name" class="col-sm-2 control-label">Customer Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $data->customer_name }}" required>
                                @if ($errors->has('customer_name'))
                                    <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{ asset('customer') }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
