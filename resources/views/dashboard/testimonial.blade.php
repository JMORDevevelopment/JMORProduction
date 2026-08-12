@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
@endpush

@push('scripts')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js">
    </script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
        });

        $('#table_id').dataTable({
            "ordering": false
        });

        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
@endpush

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Testimonial Data</h3>
                    <div class="pull-right">
                        <a class="btn btn-default" href="{{ $link_add }}"><i class="fa fa-plus"></i>&nbsp;Add</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example" class="table table-bordered table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $testimony_data)
                                <tr>
                                    <td>{{ $testimony_data->id }}</td>
                                    <td>{{ $testimony_data->service_used }}</td>
                                    <td>
                                        @if($testimony_data->status == 0)
                                            <span class="label label-danger">Pending</span>
                                        @elseif($testimony_data->status == 1)
                                            <span class="label label-info">Approved</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tools">
                                            <a href="{{ route('dashboard.testimonial_add', $testimony_data->id) }}" class="btn btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
