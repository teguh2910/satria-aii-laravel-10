@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">        
        <div class="col-md-12">
            @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            @if(Session::has('warning'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('warning') }}</p>
            @endif
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                <li class="active"><a class=""><big><big><big><font face="calibri">Complete Invoice </font></big></big></big> <span class="label label-warning"></span></a></li>
                <li class=""><a class=""><big><big><big><font face="calibri">UnComplete Invoice </font></big></big></big> <span class="label label-warning"></span></a></li>
                </ul>
                <div class="panel-body">
                    @if(Auth::user()->name == 'ppic1' || Auth::user()->name == 'ppic2'|| Auth::user()->name == 'ppic3')
                    <a href="{{asset("/sj_balik")}}" class="btn btn-md btn-warning">Scan Disini >> SJ/DO From Customer</a>
                    <br><br>
                    @elseif(Auth::user()->name == 'finance1'||Auth::user()->name == 'finance2'||Auth::user()->name == 'finance3')                
                    <!-- <a href="{{asset("/terima_finance")}}" class="btn btn-md btn-success">FINANCE</a>
                    <br><br>
                    @endif -->
                    <!-- <form method='post' action='{{asset('filter_view')}}' class="pull-right">                    
                    {{ csrf_field() }}
                        <div class='container-fluit'>
                        <div class='col-md-5'>
                        <label>FROM</label>
                        <input type='datetime-local' name='from' class='form-control'>
                        </div>
                        <div class='col-md-5'>
                        <label>TO</label>
                        <input type='datetime-local' name='to' class='form-control'>
                        </div>
                        <div class='col-md-2'>
                        <label>&nbsp;</label> <br>
                        <input type='submit' class='btn btn-md btn-primary'>
                        </div>
                        </div>
                    </form>
                    <br><br> -->
                    <table id="sj_invoice" class="table table-bordered table-condensed table-hover dt-responsive" width="100%">
                <thead>                 
                <tr class="info">
                <th><small>Invoice</small></th>    
                <th><small>Jumlah SJ/DO Terima Finance</small></th>    
                <th><small>Jumlah SJ/DO SAP</small></th>                
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
initSatriaDataTable('#sj_invoice', {
processing: true,
serverSide: true,
ajax: satriaAjaxConfig('{!!url("data_invoice")!!}')
});
});
</script>
@endsection
