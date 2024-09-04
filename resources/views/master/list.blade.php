@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-database"></i></div>
                            Master Customer
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">MKM Master Customer List</div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <!-- Page header content here, if needed -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">List of Master Customer</h3>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <a href="{{ url('/mst/customer/form') }}">
                                                <button type="button" class="btn btn-dark btn-sm mb-2">
                                                    <i class="fas fa-plus-square"></i>
                                                </button>
                                            </a>

                                            <!-- Add Modal -->
                                            <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modal-add-label">Add Asset</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ url('/mst/form') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="">Select Form</label>
                                                                            <select name="form" id="status" class="form-control" required>
                                                                                <option value="">- Please Select From -</option>
                                                                                @foreach ($dropdown as $status)
                                                                                    <option value="{{ $status->name_value }}">{{ $status->name_value }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Add Modal -->

                                            <!-- Alerts and Form Validation Messages -->
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
                                            <!-- End Alerts and Form Validation Messages -->

                                        </div>
                                    </div>

                                    <div class="table">
                                        <table id="tableUser" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Account Number</th>
                                                    <th>Log Comments</th>
                                                    <th>Approver Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($item as $data)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $data->name }}</td>
                                                        <td>{{ $data->customer_account_number ?? 'Unregistered' }}</td>
                                                        <td>{{ $data->latestApprovalLog->approval_comments ?? 'No comments' }}</td>
                                                        <td>{{ $data->latestApprovalLog->approver->name ?? 'No approver' }}</td>

                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <button class="dropdown-item" title="Delete" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                                                            <i class="fas fa-trash-alt"></i> Delete
                                                                        </button>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ url('customer/detail/'.encrypt($data->id)) }}" title="Detail">
                                                                            <i class="fas fa-info"></i> Detail
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="/customer/update/{{ encrypt($data->id) }}" title="Update">
                                                                            <i class="fas fa-pen"></i> Update
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <button class="dropdown-item" title="Show Log" data-bs-toggle="modal" data-bs-target="#modal-log{{ $data->id }}">
                                                                            <i class="fas fa-list"></i> Show Log
                                                                        </button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    {{-- Modal Delete --}}
                                                    <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Customer</h4>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('/mst/delete/'.encrypt($data->id)) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            Are you sure you want to delete <label for="rule">{{ $data->name }}</label>?
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Delete --}}



                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                 <!-- Customer Log Modal -->
                                 <div class="modal fade" id="modal-log{{ $data->id }}" tabindex="-1" aria-labelledby="modal-logLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal-logLabel{{ $data->id }}">Customer Log</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display the log here -->
                                                <table id="tableLog{{ $data->id }}" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Approver</th>
                                                            <th>Action</th>
                                                            <th>Comments</th>
                                                            <th>Timestamp</th>
                                                            <th>Level</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data->changes as $change)
                                                            @foreach($change->approvalLogs as $log)
                                                                <tr class="
                                                                @if($log->approval_action === 'checked')
                                                                    table-success
                                                                @elseif($log->approval_action === 'remand')
                                                                    table-danger
                                                                @elseif($log->approval_action === 'Submitter')
                                                                    table-primary
                                                                @endif
                                                            ">
                                                                    <td>{{ $log->approver->name ?? 'No approver' }}</td>
                                                                    <td>{{ $log->approval_action }}</td>
                                                                    <td>{{ $log->approval_comments }}</td>
                                                                    <td>{{ $log->approval_timestamp }}</td>
                                                                    <td>{{ $log->approval_level }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Customer Log Modal -->
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
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
</main>

<!-- For Datatables -->
<script>
    $(document).ready(function() {
        var table = $("#tableUser").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false
        });
    });
</script>
@endsection
