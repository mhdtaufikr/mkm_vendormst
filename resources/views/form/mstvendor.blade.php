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
                                    <h3 class="card-title">Vendor Master Form</h3>
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

                                    <form method="POST" action="{{ url('/vendor/store') }}">
                                        @csrf
                                        <h3><strong>Vendor Master Maintenance Request Form</strong></h3>
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="vendor_account_number" class="form-label">Vendor Account Number</label>
                                                        <input type="text" class="form-control" id="vendor_account_number" name="vendor_account_number" required>
                                                        <small class="text-danger form-text">(Enter vendor account number only for vendor employee and vendor inter company)</small>
                                                    </div>
                                                    <label class="form-label">Account Group</label><br>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="local_vendor" value="local_vendor" required>
                                                                    <label class="form-check-label" for="local_vendor">MKM Local Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="trade_vendor" value="trade_vendor">
                                                                    <label class="form-check-label" for="trade_vendor">MKM Trade Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="overseas_vendor" value="overseas_vendor">
                                                                    <label class="form-check-label" for="overseas_vendor">MKM Overseas Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="non_trade_vendor" value="non_trade_vendor">
                                                                    <label class="form-check-label" for="non_trade_vendor">MKM Non Trade Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="mkm_third_parties" value="mkm_third_parties">
                                                                    <label class="form-check-label" for="mkm_third_parties">MKM Third Parties</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">

                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="general_affairs_vendor" value="general_affairs_vendor">
                                                                    <label class="form-check-label" for="general_affairs_vendor">MKM General Affairs Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="others_individual_vendor" value="others_individual_vendor">
                                                                    <label class="form-check-label" for="others_individual_vendor">MKM Others / Individual Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="employee_vendor" value="employee_vendor">
                                                                    <label class="form-check-label" for="employee_vendor">MKM Employee Vendor</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="mkm_related_parties" value="mkm_related_parties">
                                                                    <label class="form-check-label" for="mkm_related_parties">MKM Related Parties</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="account_group" id="government_related_vendor" value="government_related_vendor">
                                                                    <label class="form-check-label" for="government_related_vendor">Government Related Vendor</label>
                                                                </div>

                                                            </div>
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
                                                            <label for="previous_sap_vendor_number" class="form-label">Previous SAP Vendor Number</label>
                                                            <input type="text" class="form-control" id="previous_sap_vendor_number" name="previous_sap_vendor_number">
                                                        </div>

                                                       <small class="text-danger form-text">Note<span class="text-danger">*</span> : For Change, Block, Delete please enter the previous SAP Vendor number</small>

                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                        <hr class="my-4">

                                        <h3><strong> Reference (only use for copy vendor with the same description)</strong></h1>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="company_code" class="form-label">Company Code</label>
                                                <input type="text" class="form-control" id="company_code" name="company_code"  required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="vendor_name" class="form-label">Vendor Name</label>
                                                <input type="text" class="form-control" id="vendor_name" name="vendor_name" required>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h3><strong>Create Vendor Address</strong></h3>

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

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="search_term_1" class="form-label">Search Term 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="search_term_1" name="search_term_1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="search_term_2" class="form-label">Search Term 2</label>
                                                <input type="text" class="form-control" id="search_term_2" name="search_term_2">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="street" class="form-label">Street<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="street" name="street" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="house_number" class="form-label">House Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="house_number" name="house_number" required>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="postal_code" class="form-label">Postal Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="city" name="city" required>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="country" class="form-label">Country<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="country" name="country" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="region" class="form-label">Region<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="region" name="region">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="po_box" class="form-label">P.O. Box<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="po_box" name="po_box">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="telephone" class="form-label">Telephone<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <label for="fax" class="form-label">Fax</label>
                                                <input type="text" class="form-control" id="fax" name="fax">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h3><strong>Create Vendor Control</strong></h3>

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
                                                    <input class="form-check-input" type="radio" name="tax_code" id="no-npwp" value="No NPWP<span class="text-danger">*</span>">
                                                    <label class="form-check-label" for="no-npwp">No NPWP<span class="text-danger">*</span></label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="npwp" class="form-label">NPWP<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="npwp" name="npwp" required>
                                                <br>
                                                <small class="text-danger form-text"><span class="text-danger">*</span> Tidak memiliki NPWP tidak dapat didaftarkan</small>
                                            </div>

                                        </div>

                                        <hr class="my-4">

                                        <h3><strong>Create Vendor Payment Control</strong></h3>

                                        <div class="row">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="country" class="form-label">Country<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="country" name="country" required>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="bank_key" class="form-label">Bank Key</label>
                                                        <input type="text" class="form-control" id="bank_key" name="bank_key">
                                                    </div>



                                                    <div class="col-md-12 mb-3">
                                                        <label for="bank_account" class="form-label">Bank Account<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bank_account" name="bank_account" required>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="account_holder" class="form-label">Account Holder<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="account_holder" name="account_holder" required>
                                                    </div>




                                                    <div class="col-md-12 mb-3">
                                                        <label for="bank_region" class="form-label">Bank Region<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bank_region" name="bank_region">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="col-md-12 mb-3">
                                                        <!-- The table layout starts here -->
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">No</th>
                                                                    <th scope="col">CURR</th>
                                                                    <th scope="col">ACC NUMBER</th>
                                                                    <th scope="col">ATAS NAMA</th>
                                                                    <th scope="col">THE BANK OF</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><textarea class="form-control" name="no" rows="1"></textarea></td>
                                                                    <td><textarea class="form-control" name="curr" rows="1"></textarea></td>
                                                                    <td><textarea class="form-control" name="acc_number" rows="1"></textarea></td>
                                                                    <td><textarea class="form-control" name="atas_nama" rows="1"></textarea></td>
                                                                    <td><textarea class="form-control" name="the_bank_of" rows="1"></textarea></td>
                                                                </tr>
                                                                <!-- Add more table rows here if needed -->
                                                            </tbody>
                                                        </table>
                                                        <!-- The table layout ends here -->
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="confirm_with" class="form-label">CONFIRM WITH</label>
                                                        <input type="text" class="form-control" id="confirm_with" name="confirm_with">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="date" class="form-label">DATE</label>
                                                        <input type="date" class="form-control" id="date" name="date">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="confirmed_by" class="form-label">CONFIRMED BY</label>
                                                        <input type="text" class="form-control" id="confirmed_by" name="confirmed_by">
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h3><strong>Create Vendor Accounting Information </strong></h3>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="recon_account" class="form-label">Recon Account</label>
                                                <input type="text" class="form-control" id="recon_account" name="recon_account" required>
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
                                        <hr class="my-4">

                                        <h3><strong>Create Vendor Payment Transaction</strong></h3>

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
                                                <label for="withholding_tax" class="form-label">Withholding Tax</label>
                                                <select class="form-control" id="withholding_tax" name="withholding_tax" required>
                                                    <option value="">-- Select Withholding Tax --</option>
                                                    <option value="wht_23_2_1">W/H Tax 23 (2%) Jasa aktuaris</option>
                                                    <option value="wht_23_2_2">W/H Tax 23 (2%) Jasa akuntansi, pembukuan, dan atestasi laporan keuangan</option>
                                                    <option value="wht_23_2_3">W/H Tax 23 (2%) Jasa pengolahan limbah, pembasmian hama</option>
                                                    <option value="wht_23_2_4">W/H Tax 23 (2%) Jasa penyedia tenaga kerja dan atau tenaga ahli</option>
                                                    <option value="wht_23_2_5">W/H Tax 23 (2%) Jasa perantara dan atau keagenan, pengurusan dokumen</option>
                                                    <option value="wht_23_2_6">W/H Tax 23 (2%) Jasa sehubungan dengan software atau hardware</option>
                                                    <option value="wht_23_2_7">W/H Tax 23 (2%) Jasa pemasangan/perawatan mesin, peralatan, listrik, telepon, air, gas, AC</option>
                                                    <option value="wht_23_2_8">W/H Tax 23 (2%) Jasa sewa/perawatan kendaraan dan atau alat transportasi</option>
                                                    <option value="wht_23_2_9">W/H Tax 23 (2%) Jasa maklon</option>
                                                    <option value="wht_23_2_10">W/H Tax 23 (2%) Jasa kebersihan atau cleaning service</option>
                                                    <option value="wht_23_2_11">W/H Tax 23 (2%) Jasa katering atau tata boga</option>
                                                    <option value="wht_23_2_12">W/H Tax 23 (2%) Jasa freight forwarding</option>
                                                    <option value="wht_23_2_13">W/H Tax 23 (2%) Jasa pengepakan, loading dan unloading</option>
                                                    <option value="wht_23_2_14">W/H Tax 23 (2%) Jasa sertifikasi</option>
                                                    <option value="wht_26_10">W/H Tax 26 (10%) Sewa Tanah / Bangunan</option>
                                                    <option value="wht_4_2">W/H Tax 4 (2%) Jasa Konstruksi dengan KLU Kecil</option>
                                                    <option value="wht_4_3">W/H Tax 4 (3%) Jasa Konstruksi dengan KLU Menengah dan Besar</option>
                                                    <option value="wht_4_4">W/H Tax 4 (4%) Jasa Konstruksi tidak memiliki KLU</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="payment_block" class="form-label">Payment Block</label>
                                                <input type="checkbox" class="form-check-input" id="payment_block" name="payment_block">
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
