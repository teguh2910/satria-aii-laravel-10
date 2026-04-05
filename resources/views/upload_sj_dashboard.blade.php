@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">
        <div class="col-md-6 col-md-offset-3"  style="border: 4px solid #a1a1a1;">
        	<h2><center><font color="white">Upload SJ </font></center></h2>
            <center><form action="{{asset('upload/sj/dashboard')}}" class="form-horizontal" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="file" name="sj" /><br>
			<button class="btn btn-warning">Proses</button>
			<br><br>
			</center>
		</form>
        </div>
    </div>
</div>

@endsection
