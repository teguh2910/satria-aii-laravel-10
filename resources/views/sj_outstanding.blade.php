@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Outstanding SJ &gt; 7 Hari</h4>

                    <div class="table-responsive">
                        <table id="sj_ppic_more_7_days" class="table table-striped table-hover align-middle w-100">
                            <thead>
                                <tr>
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
</div>

@endsection

@section('page-scripts')
<script type="text/javascript">
$(document).ready(function() {
initSatriaDataTable('#sj_ppic_more_7_days', {
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
ajax: satriaAjaxConfig('{!!url("data_outstanding_sj_7_day")!!}')
});
});
</script>
@endsection
