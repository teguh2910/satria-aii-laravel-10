@extends('layouts.app')
@section('content')
<div class="container-full">
        <div class="row">
                <div class="col-lg-7 col-md-9 mx-auto">
                        <div class="card bg-white border-0 rounded-3 mb-4">
                                <div class="card-body p-4">
                                        <h4 class="mb-4">Edit SJ</h4>

                                        <form action="{{ asset('edit_sj/'.$data->id) }}" method="post">
                                                {{ csrf_field() }}

                                                <div class="mb-3">
                                                        <label class="form-label">Tanggal Delivery</label>
                                                        <input type="date" class="form-control" value="{{ $data->tanggal_delivery }}" name="tanggal_delivery" required>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">Customer Code</label>
                                                        <input type="text" class="form-control" value="{{ $data->customer_code }}" name="customer_code" required>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">PDS Number</label>
                                                        <input type="text" class="form-control" value="{{ $data->pdsnumber }}" name="pdsnumber" required>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">SJ/DO AII</label>
                                                        <input type="text" class="form-control" value="{{ $data->doaii }}" name="doaii" required>
                                                </div>

                                                <button type="submit" class="btn btn-success">Edit</button>
                                        </form>
                                </div>
                        </div>
        </div>
        </div>
</div>

@endsection
