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
                        <a href="{{url('/vendor/template')}}" class="btn btn-link">
                            Download Excel Format
                        </a>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Main page content-->
<div class="container-fluid px-4 mt-n10">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      {{-- <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>    </h1>
          </div>
        </div>
      </div><!-- /.container-fluid --> --}}
    </section>

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
                <div class="row">
                    <div class="mb-3 col-sm-12">
                        <a href="{{url('/mst/vendor/form')}}">
                            <button type="button" class="btn btn-dark btn-sm mb-2" >
                                <i class="fas fa-plus-square"></i>
                              </button>
                        </a>


                          <!-- Modal -->
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


                      <!--alert success -->
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

                      <!--alert success -->
                      <!--validasi form-->
                        @if (count($errors)>0)
                          <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              <ul>
                                  <li><strong>Data Process Failed !</strong></li>
                                  @foreach ($errors->all() as $error)
                                      <li><strong>{{ $error }}</strong></li>
                                  @endforeach
                              </ul>
                          </div>
                        @endif
                      <!--end validasi form-->
                </div>
                <div class="table">
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
                        <tbody>
                            @php
                              $no=1;
                            @endphp
                            @foreach ($items as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->vendor_account_number ?? 'Unregistered' }}</td>
                                <td>
                                    @foreach ($data->vendorChanges as $change)
                                        <div>
                                            <strong class="{{ $change->latestPending == 'Approved' ? 'text-success' : 'text-warning' }}">
                                                {{ $change->latestPending == 'Approved' ? 'Approved' : 'Pending at ' . $change->latestPending }}
                                            </strong>

                                            <button class="btn btn-link btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#approvalRoute{{ $change->id }}" aria-expanded="false" aria-controls="approvalRoute{{ $change->id }}">
                                                Show All
                                            </button>
                                            <div class="collapse" id="approvalRoute{{ $change->id }}">
                                                <ul class="list-unstyled">
                                                    @php
                                                        // Group approval routes by dept and level for internal use
                                                        $groupedRoutes = $change->approvalRoutes->groupBy(function($route) {
                                                            return $route->dept . ' (Level ' . $route->level . ')';
                                                        });
                                                    @endphp
                                                    @foreach ($groupedRoutes as $groupKey => $routes)
                                                        @php
                                                            // Extract the department name only, ignoring the level in the display
                                                            $displayDept = explode(' (Level', $groupKey)[0];
                                                        @endphp
                                                        <li>
                                                            <strong>{{ $displayDept }}:</strong>
                                                            @foreach ($routes as $route)
                                                                {{ $route->name }} -
                                                                @if ($route->status == 'Approved')
                                                                    <span class="text-success">(Approved)</span>
                                                                    @if ($route->timestamp)
                                                                    <span class="text-muted">{{ date('d/m/Y, H:i', strtotime($route->timestamp)) }}</span>
                                                                    @endif
                                                                @elseif ($route->status == 'Pending')
                                                                    <span class="text-warning">(Pending)</span>
                                                                @else
                                                                    <span class="text-secondary">(Not yet reviewed)</span>
                                                                @endif
                                                                @if (!$loop->last)
                                                                    , <!-- Add a comma separator between approvers -->
                                                                @endif
                                                            @endforeach
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>





                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('vendor/detail/'.encrypt($data->id)) }}" title="Detail">
                                                    <i class="fas fa-info"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/vendor/update/{{ encrypt($data->id) }}" title="Update">
                                                    <i class="fas fa-pen"></i> Update
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" title="Delete" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
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
                            @endforeach
                        </tbody>
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
    @foreach ($items as $data)
<!-- Log Modal -->
 <div class="modal fade" id="modal-log{{ $data->id }}" tabindex="-1" aria-labelledby="modal-logLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-logLabel">Supplier Log</h5>
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
                        @foreach($data->vendorChanges as $change)
                            @foreach($change->logs as $log)

                                <tr class="
                                @if($log->approval_action === 'checked')
                                    table-success
                                @elseif($log->approval_action === 'remand')
                                    table-danger
                                @elseif($log->approval_action === 'Submitter')
                                    table-primary
                                @endif
                            ">
                                    <td>{{ $log->approver->name }}</td>
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
@endforeach
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
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
    });
  </script>
  <script>
    // For Datatables
$(document).ready(function() {
    // Attach a click event to the log buttons
    $('[data-bs-target^="#modal-log"]').on('click', function() {
        var target = $(this).data('bs-target');
        var tableId = $(target).find('table').attr('id');

        // Check if the DataTable instance already exists
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            // Destroy the existing instance
            $('#' + tableId).DataTable().destroy();
        }

        // Initialize DataTable
        $('#' + tableId).DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [[3, "desc"]] // Default sorting on the 4th column (index 3) - approval_timestamp
        });
    });
});

  </script>
@endsection

