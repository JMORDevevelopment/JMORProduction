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
			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">First name:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" name="firstname" id="input" value="{{ $user->firstname }}" placeholder="firstname">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">Last name:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" name="lastname" id="input" value="{{ $user->lastname }}" placeholder="lastname">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">E-mail:</label>
			  <div class="col-sm-10">
				<input type="text" disabled class="form-control" name="email" id="input" value="{{ $user->email }}" placeholder="email">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">password:</label>
			  <div class="col-sm-10">
				<input type="password" class="form-control" name="password" id="input" placeholder="password">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">City:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" name="city" id="input" value="{{ $user->city }}" placeholder="city">
			  </div>
			</div>

			<div class="form-group">
			  <label for="input" class="col-sm-2 control-label">Address:</label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" name="address" id="input" value="{{ $user->address }}" placeholder="address">
			  </div>
			</div>

			<div class="form-group">
				  <label for="input" class="col-sm-2 control-label">State:</label>
				  <div class="col-sm-10">
				<input type="text" class="form-control" name="state" id="input" value="{{ $user->state }}" placeholder="state">
			</div>
			</div>

			<div class="form-group">
				  <label for="input" class="col-sm-2 control-label">zip:</label>
				  <div class="col-sm-10">
				<input type="text" class="form-control" name="zip" id="input" value="{{ $user->zip }}" placeholder="zip">
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
