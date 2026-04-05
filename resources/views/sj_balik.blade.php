@extends('layouts.app')
@section('content')
<div class="container-full">
    @if(Session::has('message'))
        <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
    @endif
    @if(Session::has('danger'))
        <div class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('danger') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-7 col-md-9 mx-auto">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Scan Barcode -- SJ/DO From Customer</h4>

                    <form action="{{ asset('sj_balik') }}" method="post" class="mb-4">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label class="form-label">Scan Barcode</label>
                            <input type="text" class="form-control" placeholder="Scan Barcode" name="doaii" autofocus required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Scan</button>
                    </form>

                    <hr>

                    <form action="{{ asset('update_sj_balik_ppic_upload') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label class="form-label">Upload Data Scan</label>
                            <input type="file" name="update_sj_balik_ppic" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning">Upload Data Scan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
