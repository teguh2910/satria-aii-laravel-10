@extends('layouts.app')
@section('content')
<div class="container-full">
        <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                        <div class="card bg-white border-0 rounded-3 mb-4">
                                <div class="card-body p-4">
                                        <h4 class="mb-4">Create SJ</h4>

                                        <form action="{{ asset('create/sj') }}" method="post">
                                                {{ csrf_field() }}

                                                <div class="mb-3">
                                                        <label class="form-label">Tanggal Delivery</label>
                                                        <input type="date" class="form-control" name="tanggal_delivery" required>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">Customer Name</label>
                                                        <select name="customer_code" class="form-control" required>
                                                                @foreach($data as $row)
                                                                        <option value="{{ $row->customer_code }}">{{ $row->customer_name }}</option>
                                                                @endforeach
                                                        </select>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">PDS Number</label>
                                                        <input type="text" class="form-control" name="pdsnumber" required>
                                                </div>

                                                <div class="mb-3">
                                                        <label class="form-label">SJ/DO AII</label>
                                                        <input type="text" class="form-control" name="doaii" required>
                                                </div>

                                                <button type="submit" class="btn btn-success">Create</button>
                                        </form>
                                </div>
                        </div>
        </div>
        </div>
</div>

@endsection
