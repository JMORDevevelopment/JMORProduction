@extends('layouts.dashboard')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $testimonial ? 'Update' : 'Add' }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ $link }}" method="POST">
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
                            <label for="input" class="col-sm-2 control-label">Service:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('service_used') is-invalid @enderror" name="service_used" id="input" value="{{ old('service_used', $testimonial->service_used ?? '') }}" placeholder="Enter service here">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Message:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="input" placeholder="Enter message here">{{ old('message', $testimonial->message ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <a href="{{ $cancel }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-info pull-right">{{ $testimonial ? 'Update' : 'Add' }}</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection
