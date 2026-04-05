@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">SJ/DO Error Log</h4>

                    <div class="table-responsive">
                        <table id="sj_error_table" class="table table-striped table-hover align-middle w-100">
                        <thead>                 
                            <tr>
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

