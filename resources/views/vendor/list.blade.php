@extends('layouts.master')

@section('content')

<style>
    .text-success {
        color: green;
    }
    .text-danger {
        color: red;
    }
    .text-warning {
        color: orange;
    }
</style>

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-database"></i></div>
                            Master Supplier
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <button class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#uploadMasterPart">
                            <i class="fas fa-file-excel"></i> Master Supplier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal for Upload -->
    <div class="modal fade" id="uploadMasterPart" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-label">Upload Master Part</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/vendor/upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="file" class="form-control" id="csvFile" name="excel-file" accept=".csv, .xlsx">
                            <p class="text-danger">*file must be .xlsx or .csv</p>
                        </div>
                        @error('excel-file')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/vendor/template') }}" class="btn btn-link">Download Excel Format</a>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main page content -->
    <div class="container-fluid px-4 mt-n10">
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">List of Master Supplier</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="mb-3 col-sm-12">
                                        <a href="{{ url('/mst/vendor/form') }}">
                                            <button type="button" class="btn btn-dark btn-sm mb-2">Add Supplier Master <i class="fas fa-plus-square"></i></button>
                                        </a>

                                        <!-- Alert messages -->
                                        @if (session('status'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>{{ session('status') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif
                                        @if (session('failed'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>{{ session('failed') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif
                                        @if (count($errors) > 0)
                                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                <ul>
                                                    <li><strong>Data Process Failed!</strong></li>
                                                    @foreach ($errors->all() as $error)
                                                        <li><strong>{{ $error }}</strong></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive">
                                        <table id="tableUser" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Account Number</th>
                                                    <th>Approval Route</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
        </div>
        <!-- /.content-wrapper -->
    </div>
</main>

<!-- For Datatables -->
<script>
    $(document).ready(function() {
        $('#tableUser').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('mst.vendor') }}",  // Server-side data loading URL
            columns: [
                {
                    data: null, // No specific data source for this column
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Display the row number starting from 1
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row) {
                        if (data) {
                            // Capitalize the first letter of each word
                            return data.replace(/\w\S*/g, function(txt) {
                                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                            });
                        }
                        return ''; // Return empty string if no data
                    }
                },
                {
                    data: 'vendor_account_number',
                    name: 'vendor_account_number',
                    render: function(data, type, row) {
                        // Check if the data is null or empty
                        return data ? data : 'Data still not available';
                    }
                },
                {
                    data: 'approval_route',
                    name: 'approval_route',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        // Return the HTML content directly for rendering
                        return data;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false
        });
    });
</script>

@endsection
