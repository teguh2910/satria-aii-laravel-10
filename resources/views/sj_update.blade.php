@extends('layouts.app')
@section('content')
<div class="container-full">
<div class="row">
<div class="col-md-6 col-md-offset-3"  style="border: 4px solid #a1a1a1;">
        <h2><center><font color="white">EDIT SJ</font></center></h2>
        <center>
        <form action="{{asset('edit_sj/'.$data->id)}}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        <label for="">Tanggal Delivery</label>
        <input type="date" class="form-control" value="{{$data->tanggal_delivery}}" name="tanggal_delivery" />
        <label for="">Customer Code</label>
        <input type="text" class="form-control" value="{{$data->customer_code}}" name="customer_code" />
        <label for="">PDS Number</label>
        <input type="text" class="form-control" value="{{$data->pdsnumber}}" name="pdsnumber" />
        <label for="">SJ/DO AII</label>
        <input type="text" class="form-control" value="{{$data->doaii}}" name="doaii" />
        <br>
        <input type="submit" value="EDIT" class="btn btn-md btn-success">
        <br> <hr>
        </center>
        </form>       
        </div>
</div>
</div>

@endsection
