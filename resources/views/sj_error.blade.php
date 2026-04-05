@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">        
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> SJ/DO Error Log</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <table id="sj_error_table" class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                        <thead>                 
                            <tr class="bg-primary">
                                <th>No SJ/DO</th>
                                <th>User PPIC Scan</th>
                                <th>Waktu Scan</th>                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary {
        background-color: #0097bc !important;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        $('#sj_error_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/data_sj_error',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataSrc: function(json) {
                    return json.data || json.aaData || [];
                }
            },
            columns: [
                {
                    data: null,
                    name: 'doaii',
                    render: function(data, type, row) {
                        return row.doaii || row[1] || '';
                    }
                },
                {
                    data: null,
                    name: 'user_scan',
                    render: function(data, type, row) {
                        return row.user_scan || row[2] || '';
                    }
                },
                {
                    data: null,
                    name: 'created_at',
                    render: function(data, type, row) {
                        return row.created_at || row[3] || '';
                    }
                }
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Loading...',
                emptyTable: 'No error records found',
                info: 'Showing _START_ to _END_ of _TOTAL_ errors',
                infoEmpty: 'No errors to display',
                lengthMenu: 'Show _MENU_ entries'
            },
            pageLength: 50,
            lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]]
        });
    });
</script>
@endsection

