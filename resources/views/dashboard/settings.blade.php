@extends('layouts.dashboard')

@section('content')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
    </script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js">
    </script>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Setting Data</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example" class="table table-bordered table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $user->firstname . ' ' . $user->lastname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="tools">
                                        <a href="{{ route('dashboard.user_settings_update', $user->user_id) }}"
                                            class="btn btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
        <!-- /.content -->
    </div>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });

        $('#table_id').dataTable({
            "ordering": false
        });

        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
