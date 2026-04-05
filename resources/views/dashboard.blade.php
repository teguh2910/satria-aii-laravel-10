@extends('layouts.app')
@section('content')
@php
    $currentUserName = (string) optional(Auth::user())->name;
    $isPpicUser = in_array($currentUserName, ['ppic1', 'ppic2', 'ppic3'], true);
    $isFinanceUser = in_array($currentUserName, ['finance1', 'finance2', 'finance3'], true);
@endphp
<div class="container-full">
    <div class="row">        
        <div class="col-md-12">
            @if(session('message'))
            <p class="alert {{ session('alert-class', 'alert-info') }}">{{ session('message') }}</p>
            @endif
            @if(session('warning'))
            <p class="alert {{ session('alert-class', 'alert-danger') }}">{{ session('warning') }}</p>
            @endif
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                <li class="active"><a class=""><big><big><big><font face="calibri">Surat Jalan </font></big></big></big> <span class="label label-warning"></span></a></li>
                </ul>
                <div class="panel-body">
                    @if($isPpicUser)
                    <a href="{{ url('/sj_balik') }}" class="btn btn-md btn-warning">Scan Disini >> SJ/DO From Customer</a>
                    <br><br>
                    @elseif($isFinanceUser)
                    <a href="{{ url('/terima_finance') }}" class="btn btn-md btn-success">FINANCE</a>
                    <br><br>
                    @endif
                    <form method='post' action='{{ url('filter_view') }}' class="pull-right">                    
                    @csrf
                        <div class='container-fluit'>
                        <div class='col-md-5'>
                        <label>FROM</label>
                        <input type='date' name='from' class='form-control'>
                        </div>
                        <div class='col-md-5'>
                        <label>TO</label>
                        <input type='date' name='to' class='form-control'>
                        </div>
                        <div class='col-md-2'>
                        <label>&nbsp;</label> <br>
                        <input type='submit' class='btn btn-md btn-primary'>
                        </div>
                        </div>
                    </form>
                    <br><br>
                    <table id="sj_all_ppic" class="table table-bordered table-condensed table-hover dt-responsive" width="100%">
                <thead>                 
                <tr class="info">
                <th><small>ID</small></th>    
                <th><small>TANGGAL WAKTU UPLOAD</small></th>    
                <th><small>TANGGAL_DELIVERY</small></th>    
                <th><small>CUSTOMER_CODE</small></th>
                <th><small>CUSTOMER_NAME</small></th>
                <th><small>PDSNUMBER</small></th>
                <th><small>DOAII</small></th>
                <th><small>SJ BALIK</small></th>
                <th><small>FINANCE</small></th>
                <th><small>SJ Error</small></th>

            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th><input type="text" placeholder="Search Customer Code" class="form-control" /></th>
                <th></th>
                <th></th>
                <th></th>
                <th>
                    <select class="form-control column-filter" data-column="7">
                        <option value="">All</option>
                        <option value="__BLANK__">Blank</option>
                        <option value="__NOT_BLANK__">Not Blank</option>
                    </select>
                </th>
                <th>
                    <select class="form-control column-filter" data-column="8">
                        <option value="">All</option>
                        <option value="__BLANK__">Blank</option>
                        <option value="__NOT_BLANK__">Not Blank</option>
                    </select>
                </th>
                <th></th>
            </tr>
        </thead>                   
            </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var tableSelector = '#sj_all_ppic';
    var sjBalikFilter = '';
    var financeFilter = '';

    function bindFilters(table) {
        $(tableSelector).off('preXhr.dt.sjfilters').on('preXhr.dt.sjfilters', function(e, settings, data) {
            data.sj_balik_filter = sjBalikFilter;
            data.terima_finance_filter = financeFilter;
        });

        $('#sj_all_ppic thead input')
            .off('.sjfilters')
            .on('keyup.sjfilters change.sjfilters', function() {
                table.column(3).search(this.value).draw();
            });

        $('#sj_all_ppic thead select.column-filter')
            .off('.sjfilters')
            .on('change.sjfilters', function() {
                var column = parseInt($(this).data('column'), 10);
                var keyword = $(this).val();

                if (column === 7) {
                    sjBalikFilter = keyword;
                }

                if (column === 8) {
                    financeFilter = keyword;
                }

                table.draw();
            });
    }

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        bindFilters($(tableSelector).DataTable());
    } else {
        $(tableSelector).one('init.dt', function() {
            bindFilters($(tableSelector).DataTable());
        });
    }
});
</script>
@endpush

@section('page-scripts')
<script type="text/javascript">
$(document).ready(function() {
initSatriaDataTable('#sj_all_ppic', {
order: [[0, 'asc']],
processing: true,
serverSide: true,
columns: [
    { data: 'id', name: 'sjs.id', defaultContent: '' },
    { data: 'created_at', name: 'sjs.created_at', defaultContent: '' },
    { data: 'tanggal_delivery', name: 'sjs.tanggal_delivery', defaultContent: '' },
    { data: 'customer_code', name: 'sjs.customer_code', defaultContent: '' },
    { data: 'customer_name', name: 'customers.customer_name', defaultContent: '' },
    { data: 'pdsnumber', name: 'sjs.pdsnumber', defaultContent: '' },
    { data: 'doaii', name: 'sjs.doaii', defaultContent: '' },
    { data: 'sj_balik', name: 'sjs.sj_balik', defaultContent: '' },
    { data: 'terima_finance', name: 'sjs.terima_finance', defaultContent: '' },
    { data: 'updated_at', name: 'sj_errors.updated_at', defaultContent: '' }
],
ajax: satriaAjaxConfig('{!!url("data_sj")!!}')
});
});
</script>
@endsection