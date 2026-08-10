@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
	  <div class="box box-info">
		<div class="box-header with-border">
		  <h3 class="box-title">Update</h3>
		</div>
		<!-- /.box-header -->
		<!-- form start -->
		<form class="form-horizontal" action="{{ $link }}" method="POST" enctype="multipart/form-data">
		  @csrf
		  <div class="box-body">
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible">
					<h4><i class="icon fa fa-warning"></i>Please fix the following:</h4>
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">First name:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" id="input" value="{{ old('firstname', $user->firstname) }}" placeholder="firstname">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">Last name:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" id="input" value="{{ old('lastname', $user->lastname) }}" placeholder="lastname">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">E-mail:</label>
			  <div class="col-sm-10">
				<input type="text" disabled class="form-control" name="email" id="input" value="{{ old('email', $user->email) }}" placeholder="email">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">password:</label>
			  <div class="col-sm-10">
				<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="input" placeholder="password">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">City:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control @error('city') is-invalid @enderror" name="city" id="input" value="{{ old('city', $user->city) }}" placeholder="city">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">Address:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="input" value="{{ old('address', $user->address) }}" placeholder="address">
			  </div>
			</div>

			<div class="form-group">
				  <label for="input" class="col-sm-2 control-label">State:</label>
				  <div class="col-sm-10">
				<input type="text" class="form-control @error('state') is-invalid @enderror" name="state" id="input" value="{{ old('state', $user->state) }}" placeholder="state">
			</div>
			</div>

			<div class="form-group">
				  <label for="input" class="col-sm-2 control-label">zip:</label>
				  <div class="col-sm-10">
				<input type="text" class="form-control @error('zip') is-invalid @enderror" name="zip" id="input" value="{{ old('zip', $user->zip) }}" placeholder="zip">
			</div>
			</div>

		  </div>
		  <div class="box-footer">
			<a href="{{ $cancel }}" class="btn btn-default">Cancel</a>
			<button type="submit" class="btn btn-info pull-right">Update</button>
		  </div>
		  <!-- /.box-footer -->
		</form>
	  </div>
    </section>
    <!-- /.content -->
  </div>

@endsection
