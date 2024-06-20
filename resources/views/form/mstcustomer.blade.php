@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Customer Master Form</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
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

                                            <!--validasi form-->
                                            @if (count($errors) > 0)
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
                                    </div>

                                    <form method="POST" action="{{ url('/customer/store') }}">
                                        @csrf
                                        <h3><strong>Customer Master Maintenance Request Form</strong></h3>
                                        <div class="row">
                                            <!-- Your existing form fields go here -->
                                        </div>
                                        <hr class="my-4">

                                        <!-- Tabs navigation -->
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link active" id="master-tab" data-bs-toggle="tab" data-bs-target="#master" type="button" role="tab" aria-controls="master" aria-selected="false">Master</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link " id="reference-tab" data-bs-toggle="tab" data-bs-target="#reference" type="button" role="tab" aria-controls="reference" aria-selected="true">Reference</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Customer Address</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link" id="control-tab" data-bs-toggle="tab" data-bs-target="#control" type="button" role="tab" aria-controls="control" aria-selected="false">Customer Control</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false">Customer Payment Control</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link" id="accounting-tab" data-bs-toggle="tab" data-bs-target="#accounting" type="button" role="tab" aria-controls="accounting" aria-selected="false">Customer Accounting Information</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button style="color: black;" class="nav-link" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="false">Customer Payment Transaction</button>
                                            </li>

                                        </ul>

                                        <!-- Tabs content -->
                                        <div class="tab-content" id="myTabContent">

                                            <div class="tab-pane fade  show active" id="master" role="tabpanel" aria-labelledby="master-tab">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="customer_account_number" class="form-label">Customer Account Number</label>
                                                                <input type="text" class="form-control" id="customer_account_number" name="customer_account_number" required>
                                                                <small class="text-danger form-text">(Enter customer account number only for customer employee and customer inter company)</small>
                                                            </div>
                                                            <label class="form-label">Account Group</label><br>
                                                                <div class="row">
                                                                    @foreach($customerAG as $index => $group)
                                                                        <div class="col-md-6">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="checkbox" name="account_group[]" id="account_group_{{ $index }}" value="{{ $group->name_value }}">
                                                                                <label class="form-check-label" for="account_group_{{ $index }}">{{ $group->name_value }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>



                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Change Type</label><br>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="change_type" id="create" value="create" required>
                                                                    <label class="form-check-label" for="create">Create<span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="change_type" id="block" value="block">
                                                                    <label class="form-check-label" for="block">Block<span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="change_type" id="unblock" value="unblock">
                                                                    <label class="form-check-label" for="unblock">Unblock<span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="change_type" id="change" value="change">
                                                                    <label class="form-check-label" for="change">Change<span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="change_type" id="delete" value="delete">
                                                                    <label class="form-check-label" for="delete">Delete<span class="text-danger">*</span></label>
                                                                </div><br>
                                                                <label for="">Remarks / Reason :</label>
                                                                <input  type="text" class="form-control" name="Remarks" id="">
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="previous_sap_customer_number" class="form-label">Previous SAP Customer Number</label>
                                                                    <input type="text" class="form-control" id="previous_sap_customer_number" name="previous_sap_vcustomernumber">
                                                                </div>

                                                               <small class="text-danger form-text">Note<span class="text-danger">*</span> : For Change, Block, Delete please enter the previous SAP Customer number</small>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reference tab -->
                                            <div class="tab-pane fade" id="reference" role="tabpanel" aria-labelledby="reference-tab">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="company_code" class="form-label">Company Code</label>
                                                        <input readonly value="3000" type="text" class="form-control" id="company_code" name="company_code"  required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="customer_name" class="form-label">Customer Name</label>
                                                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Address tab -->
                                            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Title<span class="text-danger">*</span></label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="cv" value="cv" required>
                                                            <label class="form-check-label" for="cv">CV</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="mrs" value="mrs">
                                                            <label class="form-check-label" for="mrs">Mrs.</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="po" value="po">
                                                            <label class="form-check-label" for="po">PO</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="ms" value="ms">
                                                            <label class="form-check-label" for="ms">Ms.</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="pt" value="pt">
                                                            <label class="form-check-label" for="pt">PT</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="fa" value="fa">
                                                            <label class="form-check-label" for="fa">FA</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="pa" value="pa">
                                                            <label class="form-check-label" for="pa">PA</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="ud" value="ud">
                                                            <label class="form-check-label" for="ud">UD</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="mr" value="mr">
                                                            <label class="form-check-label" for="mr">Mr.</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="pd" value="pd">
                                                            <label class="form-check-label" for="pd">PD</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="title" id="blank" value="blank">
                                                            <label class="form-check-label" for="blank">Blank / Kosong</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="name" name="name" required>
                                                    </div>


                                                    <div class="col-md-6 mb-3">
                                                        <label for="search_term_1" class="form-label">Search Term 1<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="search_term_1" name="search_term_1" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="search_term_2" class="form-label">Search Term 2</label>
                                                        <input type="text" class="form-control" id="search_term_2" name="search_term_2">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label for="country" class="form-label">Country<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="country" name="country" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="region" class="form-label">Region</label>
                                                        <input type="text" class="form-control" id="region" name="region">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="city" name="city" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="street" class="form-label">Street<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="street" name="street" required>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label for="house_number" class="form-label">Home No.<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="house_number" name="house_number" required>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label for="postal_code" class="form-label">Postal Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label for="po_box" class="form-label">P.O. Box</label>
                                                        <input type="text" class="form-control" id="po_box" name="po_box">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label for="fax" class="form-label">Fax</label>
                                                        <input type="text" class="form-control" id="fax" name="fax">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="telephone" class="form-label">Telephone<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email" required>
                                                    </div>


                                                </div>
                                            </div>
                                            <!-- Control tab -->
                                            <div class="tab-pane fade" id="control" role="tabpanel" aria-labelledby="control-tab">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tax Code<span class="text-danger">*</span></label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="tax_code" id="wapu" value="WAPU" required>
                                                            <label class="form-check-label" for="wapu">WAPU</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="tax_code" id="non-wapu" value="NON WAPU">
                                                            <label class="form-check-label" for="non-wapu">NON WAPU</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="tax_code" id="no-npwp" value="No NPWP">
                                                            <label class="form-check-label" for="no-npwp">No NPWP</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="npwp" class="form-label">NPWP<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="npwp" name="npwp" required>
                                                        <br>
                                                        <small class="text-danger form-text"><span class="text-danger">*</span> Tidak memiliki NPWP tidak dapat didaftarkan</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Payment Control tab -->
                                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="country" class="form-label">Country<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="country" name="country" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bank_key" class="form-label">Bank Key</label>
                                                        <input type="text" class="form-control" id="bank_key" name="bank_key">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bank_account" class="form-label">Bank Account<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bank_account" name="bank_account" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="account_holder" class="form-label">Account Holder<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="account_holder" name="account_holder" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bank_region" class="form-label">Bank Region<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bank_region" name="bank_region" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="confirm_with" class="form-label">CONFIRM WITH</label>
                                                        <input type="text" class="form-control" id="confirm_with" name="confirm_with">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="date" class="form-label">DATE</label>
                                                        <input type="date" class="form-control" id="date" name="date">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="confirmed_by" class="form-label">CONFIRMED BY</label>
                                                        <input type="text" class="form-control" id="confirmed_by" name="confirmed_by">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Accounting Information tab -->
                                            <div class="tab-pane fade" id="accounting" role="tabpanel" aria-labelledby="accounting-tab">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="recon_account" class="form-label">Recon Account</label>
                                                        <input type="text" class="form-control" id="recon_account" name="recon_account">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="sort_key" class="form-label">Sort Key</label>
                                                        <input type="text" class="form-control" id="sort_key" name="sort_key">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="cash_management_group" class="form-label">Cash Management Group</label>
                                                        <input type="text" class="form-control" id="cash_management_group" name="cash_management_group">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Payment Transaction tab -->
                                            <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="payment_terms" class="form-label">Payment Terms</label>
                                                        <input type="text" class="form-control" id="payment_terms" name="payment_terms" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Payment Method</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" required>
                                                            <label class="form-check-label" for="cash">Cash</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer">
                                                            <label class="form-check-label" for="transfer">Transfer</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="cheque" value="cheque">
                                                            <label class="form-check-label" for="cheque">Cheque / Giro</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Withholding Tax</label>
                                                        @foreach($tax as $option)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="withholding_tax[]" id="{{ $option->code_format }}" value="{{ $option->name_value }}">
                                                                <label class="form-check-label" for="{{ $option->code_format }}">
                                                                    {{ $option->name_value }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>



                                                    <div class="col-md-6 mb-3">
                                                        <label for="payment_block" class="form-label">Payment Block</label>
                                                        <input type="checkbox" class="form-check-input" id="payment_block" name="payment_block">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                        </div>

                                    </form>
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
@endsection


