@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">
		<div class="col-lg-6 col-md-8 mx-auto">
			<div class="card bg-white border-0 rounded-3 mb-4">
				<div class="card-body p-4">
					<h4 class="mb-4">Upload SJ</h4>

					<form action="{{ asset('upload/sj/dashboard') }}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}

						<div class="mb-3">
							<label class="form-label">Pilih File SJ</label>
							<input type="file" name="sj" class="form-control" required>
						</div>

						<button type="submit" class="btn btn-warning">Proses</button>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>

@endsection
