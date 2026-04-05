@extends('layouts.app')
@section('content')
@php
    $currentUserName = (string) optional(Auth::user())->name;
    $isPpicUser = in_array($currentUserName, ['ppic1', 'ppic2', 'ppic3'], true);
    $isFinanceUser = in_array($currentUserName, ['finance1', 'finance2', 'finance3'], true);
@endphp
<div class="container-full">
    <div class="row">
        <div class="col-12">
            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
            @endif
            @if(Session::has('warning'))
                <div class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('warning') }}</div>
            @endif

            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                        <h4 class="mb-0">Invoice Summary</h4>

                        @if($isPpicUser)
                            <a href="{{ asset('/sj_balik') }}" class="btn btn-warning">Scan Disini >> SJ/DO From Customer</a>
                        @elseif($isFinanceUser)
                            <a href="{{ asset('/terima_finance') }}" class="btn btn-success">Finance</a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table id="sj_invoice" class="table table-striped table-hover align-middle w-100">
                            <thead>
                                <tr>
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
</div>

@endsection

@section('page-scripts')
<script type="text/javascript">
$(document).ready(function() {
initSatriaDataTable('#sj_invoice', {
processing: true,
serverSide: true,
columns: [
    { data: 'invoice', name: 'invoice', defaultContent: '' },
    { data: 'terima_finance_count', name: 'terima_finance_count', defaultContent: '' },
    { data: 'do_aii_count', name: 'do_aii_count', defaultContent: '' }
],
ajax: satriaAjaxConfig('{!!url("data_invoice")!!}')
});
});
</script>
@endsection
