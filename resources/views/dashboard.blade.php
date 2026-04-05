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
            @if(session('message'))
                <div class="alert {{ session('alert-class', 'alert-info') }}">{{ session('message') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert {{ session('alert-class', 'alert-danger') }}">{{ session('warning') }}</div>
            @endif

            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                        <h4 class="mb-0">Surat Jalan</h4>

                        <form method="post" action="{{ url('filter_view') }}" class="w-100">
                            @csrf
                            <div class="row g-2 justify-content-end align-items-end">
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label mb-1">From</label>
                                    <input type="date" name="from" class="form-control">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label mb-1">To</label>
                                    <input type="date" name="to" class="form-control">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($isPpicUser)
                        <a href="{{ url('/sj_balik') }}" class="btn btn-warning mb-3">Scan Disini >> SJ/DO From Customer</a>
                    @elseif($isFinanceUser)
                        <a href="{{ url('/terima_finance') }}" class="btn btn-success mb-3">Finance</a>
                    @endif

                    <div class="table-responsive">
                        <table id="sj_all_ppic" class="table table-striped table-hover align-middle w-100">
                            <thead>
                                <tr>
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