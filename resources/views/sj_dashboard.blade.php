@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">        
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                <li class="active"><a class=""><big><big><big><font face="calibri">Surat Jalan </font></big></big></big> <span class="label label-warning"></span></a></li>
                </ul>
                <div class="panel-body">
                
                    @if(Auth::user()->name == 'ppic' || Auth::user()->name == 'pc')
                    <a href="{{asset("/sj_balik")}}" class="btn btn-md btn-warning">Scan Disini >> SJ/DO From Customer</a>
                    <br><br>
                    @elseif(Auth::user()->name == 'finance')                
                    <a href="{{asset("/terima_finance")}}" class="btn btn-md btn-success">SCAN FINANCE</a>
                    <br><br>
                    @endif
                    @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @elseif(Session::has('danger'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('danger') }}</p>
                    @endif
                    <table id="sj_ppic" class="table table-bordered table-condensed table-hover dt-responsive" width="100%">
                <thead>                 
                <tr class="info">
                <th><small>TANGGAL WAKTU UPLOAD</small></th>    
                <th><small>TANGGAL_DELIVERY</small></th>    
                <th><small>CUSTOMER_CODE</small></th>
                <th><small>CUSTOMER_NAME</small></th>
                <th><small>PDSNUMBER</small></th>
                <th><small>DOAII</small></th>
                <th><small>SJ BALIK</small></th>
                <th><small>FINANCE</small></th>                
            </tr>
        </thead>                    
            </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-scripts')
<script type="text/javascript">
$(document).ready(function() {
initSatriaDataTable('#sj_ppic', {
processing: true,
serverSide: true,
columns: [
    { data: 'created_at', name: 'sjs.created_at', defaultContent: '' },
    { data: 'tanggal_delivery', name: 'sjs.tanggal_delivery', defaultContent: '' },
    { data: 'customer_code', name: 'sjs.customer_code', defaultContent: '' },
    { data: 'customer_name', name: 'customers.customer_name', defaultContent: '' },
    { data: 'pdsnumber', name: 'sjs.pdsnumber', defaultContent: '' },
    { data: 'doaii', name: 'sjs.doaii', defaultContent: '' },
    { data: 'sj_balik', name: 'sjs.sj_balik', defaultContent: '' },
    { data: 'terima_finance', name: 'sjs.terima_finance', defaultContent: '' }
],
ajax: satriaAjaxConfig('{!!url("data_outstanding_sj")!!}')
});
});
</script>
@endsection
