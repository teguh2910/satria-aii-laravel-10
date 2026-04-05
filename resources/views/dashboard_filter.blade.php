@extends('layouts.app')
@section('content')
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
                    <h4 class="mb-4">Surat Jalan</h4>

                    <div class="table-responsive">
                        <table id="sj_filter" class="table table-striped table-hover align-middle w-100">
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
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->created_at }}</td>
                                        <td>{{ $row->tanggal_delivery ? \Carbon\Carbon::parse($row->tanggal_delivery)->format('Y-m-d') : '' }}</td>
                                        <td>{{ $row->customer_code }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->pdsnumber }}</td>
                                        <td>{{ $row->doaii }}</td>
                                        <td>{{ $row->sj_balik ? $row->sj_balik : ($row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d H:i') : '') }}</td>
                                        <td>{{ $row->terima_finance }}</td>
                                        <td>{{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d H:i') : '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
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
    var tableSelector = '#sj_filter';
    var sjBalikFilter = '';
    var financeFilter = '';

    function bindFilters(table) {
        $('#sj_filter thead input')
            .off('.sjfilters')
            .on('keyup.sjfilters change.sjfilters', function() {
                table.column(3).search(this.value).draw();
            });

        $('#sj_filter thead select.column-filter')
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

                applyBlankFilters(table);
            });
    }

    function applyBlankFilters(table) {
        $.fn.dataTable.ext.search.length = 0;
        
        if (sjBalikFilter !== '' || financeFilter !== '') {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'sj_filter') {
                    return true;
                }

                // Filter SJ BALIK (column 7)
                if (sjBalikFilter !== '') {
                    var sjBalikData = data[7] ? data[7].trim() : '';
                    if (sjBalikFilter === '__BLANK__' && sjBalikData !== '') {
                        return false;
                    }
                    if (sjBalikFilter === '__NOT_BLANK__' && sjBalikData === '') {
                        return false;
                    }
                }

                // Filter FINANCE (column 8)
                if (financeFilter !== '') {
                    var financeData = data[8] ? data[8].trim() : '';
                    if (financeFilter === '__BLANK__' && financeData !== '') {
                        return false;
                    }
                    if (financeFilter === '__NOT_BLANK__' && financeData === '') {
                        return false;
                    }
                }

                return true;
            });
        }

        table.draw();
    }

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        bindFilters($(tableSelector).DataTable());
    } else {
        $(tableSelector).one('init.dt.sjfilter', function() {
            bindFilters($(tableSelector).DataTable());
        });
    }
});
</script>
@endpush

@section('page-scripts')
<script type="text/javascript">
$(document).ready(function() {
initSatriaDataTable('#sj_filter');
});
</script>
@endsection
