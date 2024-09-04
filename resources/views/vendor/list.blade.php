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
                            Master Vendor
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">MKM Master Vendor List</div>
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
                <h3 class="card-title">List of Master Vendor</h3>
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
                    <th>Vendor Name</th>
                    <th>Vendor Account Number</th>
                    <th>Log Comments</th>
                    <th>Approver Name</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($item as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->vendor_account_number ?? 'Unregistered' }}</td>
                        <td>{{ $data->vendorChanges->last()->logs->last()->approval_comments ?? 'No comments' }}</td>
                        <td>{{ $data->vendorChanges->last()->logs->last()->approver->name ?? 'No approver' }}</td>

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

                    {{-- Modal Update --}}
                    <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Rule</h4>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/mst/update/'.encrypt($data->id)) }}" method="POST">
                              @csrf
                              @method('post')
                              <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="">Asset No.</label>
                                            <input value="{{$data->asset_no}}" type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Asset No." required>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="">Asset Name</label>
                                            <input value="{{$data->name}}" type="text" class="form-control" id="asset_name" name="asset_name" placeholder="Enter Asset Name" required>
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="">Asset Description</label>
                                            <textarea  class="form-control" name="asset_description" cols="30" rows="5" required>{{$data->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="qty">Asset Quantity</label>
                                            <input value="{{$data->qty}}" class="form-control" name="qty" type="number" value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Asset Status</label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="{{$data->status}}">{{$data->status}}</option>
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
                                <button type="submit" class="btn btn-primary">Update</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    {{-- Modal Update --}}

                    {{-- Modal Delete --}}
                    <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Asset</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/mst/delete/'.encrypt($data->id)) }}" method="POST">
                            @csrf
                            @method('post')
                            <div class="modal-body">
                                <div class="form-group">
                                Are you sure you want to delete <label for="rule">{{ $data->name }}</label>?
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
                    {{-- Modal Delete --}}

                    {{-- Modal Access --}}
                    <div class="modal fade" id="modal-access}">
                      <div class="modal-dialog">
                          <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Give User Access</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <form action="{{ url('') }}" enctype="multipart/form-data" method="GET">
                          @csrf
                          <div class="modal-body">

                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-dark btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Submit">
                          </div>
                          </form>
                          </div>
                          <!-- /.modal-content -->
                      </div>
                    <!-- /.modal-dialog -->
                    </div>
                    {{-- Modal Revoke --}}

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
    @foreach ($item as $data)
<!-- Log Modal -->
 <div class="modal fade" id="modal-log{{ $data->id }}" tabindex="-1" aria-labelledby="modal-logLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-logLabel">Vendor Log</h5>
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

