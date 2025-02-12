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
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title m-0">Supplier Master Form</h3>
                                    <div class="d-flex gap-2">
                                        <!-- Buttons -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remandModal">
                                            Remand
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                            Approve
                                        </button>

                                    </div>
                                </div>


                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="approveModalLabel">Approve Action</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ url('vendor/approval') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <input hidden type="text" name="id" value="{{ $data->id }}">
                                                    <input hidden type="text" name="action" value="checked">
                                                    <input hidden type="text" id="hidden_vendor_account_number" name="vendor_account_number">
                                                    <p>Are you sure you want to approve this item?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.getElementById('approveModal').addEventListener('show.bs.modal', function () {
                                        var accountNumber = document.getElementById('vendor_account_number').value;
                                        document.getElementById('hidden_vendor_account_number').value = accountNumber;
                                    });

                                    // Opsi lain, saat tombol submit diklik
                                    document.querySelector('[type="submit"]').addEventListener('click', function () {
                                        var accountNumber = document.getElementById('vendor_account_number').value;
                                        document.getElementById('hidden_vendor_account_number').value = accountNumber;
                                    });
                                    </script>


                                <!-- Remand Modal -->
                                <div class="modal fade" id="remandModal" tabindex="-1" aria-labelledby="remandModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remandModalLabel">Remand Action</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{url('vendor/approval')}}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <input hidden type="text" name="id" value="{{$data->id}}">
                                                    <input hidden type="text" name="action" value="remand">

                                                    <!-- Dropdown for selecting approver to remand -->
                                                    <div class="mb-3">
                                                        <label for="remand_to" class="form-label">Remand To</label>
                                                        <select class="form-control" id="remand_to" name="remand_to">
                                                            @php
                                                                $approvers = $data->latestChange->logs->unique('approver_id')->filter(function ($log) use ($currentUserLevel) {
                                                                    return $log->approver->level < $currentUserLevel;
                                                                });
                                                            @endphp
                                                            @foreach($approvers as $log)
                                                                <option value="{{ $log->approver_id }}">{{ $log->approver->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="remarks" class="form-label">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Remand</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                   document.addEventListener('DOMContentLoaded', function () {
                                        // Approve Modal Validation
                                        const approveModal = document.getElementById('approveModal');
                                        const approveForm = approveModal.querySelector('form');
                                        const vendorAccountNumber = document.getElementById('vendor_account_number');

                                        approveForm.addEventListener('submit', function (event) {
                                            // Tambahkan kondisi untuk validasi hanya jika level pengguna adalah 7
                                            const userLevel = {{ Auth::user()->level }}; // Mendapatkan level pengguna dari server-side
                                            if (userLevel === 7 && vendorAccountNumber && !vendorAccountNumber.value.trim()) {
                                                event.preventDefault(); // Prevent form submission
                                                alert('Supplier Account Number is required for approval!');
                                            }
                                        });

                                        // Remand Modal Validation
                                        const remandModal = document.getElementById('remandModal');
                                        const remandForm = remandModal.querySelector('form');
                                        const remandTo = remandModal.querySelector('#remand_to');
                                        const remarks = remandModal.querySelector('#remarks');

                                        remandForm.addEventListener('submit', function (event) {
                                            if (remandTo && !remandTo.value.trim()) {
                                                event.preventDefault(); // Prevent form submission
                                                alert('Please select a person to remand to!');
                                                return;
                                            }

                                            if (remarks && !remarks.value.trim()) {
                                                event.preventDefault(); // Prevent form submission
                                                alert('Please provide remarks for the remand!');
                                            }
                                        });
                                    });
                                </script>

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
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                          const form = document.querySelector('form');
                                          form.addEventListener('submit', function (event) {
                                            if (!form.checkValidity()) {
                                              event.preventDefault();
                                              event.stopPropagation();

                                              // Find all invalid fields
                                              const invalidFields = Array.from(form.elements).filter(element => {
                                                return !element.checkValidity() && element.tagName.toLowerCase() !== 'button';
                                              });

                                              // Prepare a list of invalid field labels
                                              const invalidFieldLabels = invalidFields.map(field => {
                                                const label = document.querySelector(`label[for="${field.id}"]`);
                                                return label ? label.innerText : field.name;
                                              });

                                              // Display invalid field information in the modal
                                              const modalBody = document.querySelector('#validationModal .modal-body');
                                              modalBody.innerHTML = `<p>Please fill out all required fields:</p>
                                                                     <ul>${invalidFieldLabels.map(label => `<li>${label}</li>`).join('')}</ul>`;

                                              $('#validationModal').modal('show');
                                            }
                                            form.classList.add('was-validated');
                                          }, false);
                                        });
                                    </script>

                                    <!-- Your existing form HTML -->

                                    <!-- Modal HTML -->
                                    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="validationModalLabel">Form Incomplete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Please fill out all required fields.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                        @csrf
                                        <h3><strong>Supplier Master Request Form</strong></h3>
                                        <div class="row">
                                            <!-- Master Section -->
                                            <div class="col-md-6 mb-3">
                                                <label for="vendor_account_number" class="form-label">Supplier Account Number</label>
                                                <input
                                                    value="{{ $data->vendor_account_number }}"
                                                    type="text"
                                                    class="form-control @if(Auth::user()->level == 7) required @endif"
                                                    id="vendor_account_number"
                                                    name="vendor_account_number"
                                                    @if(Auth::user()->level != 7) readonly @endif
                                                    @if(Auth::user()->level == 7) required @endif
                                                >
                                                <small class="text-danger form-text">(Enter Supplier account number only for Supplier employee and Supplier inter company)</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Change Type</label><br>
                                                @foreach($types as $type)
                                                    <div class="form-check form-check-inline">
                                                        <input disabled class="form-check-input" type="radio" name="change_type" id="{{ $type->name_value }}" value="{{ $type->name_value }}" @if($data->latestChange->change_type == $type->name_value) checked @endif required>
                                                        <label class="form-check-label" for="{{ $type->name_value }}">{{ $type->name_value }}<span class="text-danger">*</span></label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="previous_sap_vendor_number" class="form-label">Previous SAP Supplier Number</label>
                                                <input readonly value="{{$data->latestChange->previous_sap_vendor_number}}" type="text" class="form-control" id="previous_sap_vendor_number" name="previous_sap_vendornumber">
                                                <small class="text-danger form-text">Note<span class="text-danger">*</span> : For Change, Block, Delete please enter the previous SAP Supplier number</small>
                                            </div>

                                            <!-- Reference Section -->
                                            <div class="col-md-6 mb-3">
                                                <label for="company_code" class="form-label">Company Code</label>
                                                <input readonly value="3000" type="text" class="form-control" id="company_code" name="company_code" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="vendor_name" class="form-label">Supplier Name</label>
                                                <input readonly value="{{$data->vendor_name}}" type="text" class="form-control" id="vendor_name" name="vendor_name">
                                            </div>

                                            <!-- Address Section -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Title<span class="text-danger">*</span></label><br>
                                                @foreach($title as $item)
                                                    <div class="form-check form-check-inline">
                                                        <input disabled class="form-check-input" type="radio" name="title" id="{{ $item->name_value }}" value="{{ $item->name_value }}" @if($data->title == $item->name_value) checked @endif required>
                                                        <label class="form-check-label" for="{{ $item->name_value }}">{{ $item->name_value }}<span class="text-danger">*</span></label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->name}}" type="text" class="form-control" id="name" name="name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="search_term_1" class="form-label">Search Term 1<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->search_term_1}}" type="text" class="form-control" id="search_term_1" name="search_term_1">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="search_term_2" class="form-label">Search Term 2</label>
                                                <input readonly value="{{$data->search_term_2}}" type="text" class="form-control" id="search_term_2" name="search_term_2">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="country" class="form-label">Country<span class="text-danger">*</span></label>
                                                <select disabled class="form-control" id="country" name="country" required>
                                                    <option value="">Select a country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country['cca3'] }}" @if($data->country == $country['cca3']) selected @endif>
                                                            {{ $country['name']['common'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="region" class="form-label">Region</label>
                                                <input readonly value="{{$data->region}}" type="text" class="form-control" id="region" name="region">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->city}}" type="text" class="form-control" id="city" name="city">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="street" class="form-label">Street<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->street}}" type="text" class="form-control" id="street" name="street">
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="house_number" class="form-label">Home No.<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->house_number}}" type="text" class="form-control" id="house_number" name="house_number">
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="postal_code" class="form-label">Postal Code<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->postal_code}}" type="text" class="form-control" id="postal_code" name="postal_code">
                                            </div>
                                            <div class="col-md-1 mb-3">
                                                <label for="po_box" class="form-label">P.O. Box</label>
                                                <input readonly value="{{$data->po_box}}" type="text" class="form-control" id="po_box" name="po_box">
                                            </div>
                                            <div class="col-md-1 mb-3">
                                                <label for="fax" class="form-label">Fax</label>
                                                <input readonly value="{{$data->fax}}" type="text" class="form-control" id="fax" name="fax">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="telephone" class="form-label">Telephone<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->telephone}}" type="text" class="form-control" id="telephone" name="telephone">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->email}}"  type="email" class="form-control" id="email" name="email">
                                            </div>

                                            <!-- Control Section -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tax Code<span class="text-danger">*</span></label><br>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="tax_code" id="wapu" value="WAPU" @if($data->tax_code == 'WAPU') checked @endif required>
                                                    <label class="form-check-label" for="wapu">WAPU</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="tax_code" id="non-wapu" value="NON WAPU" @if($data->tax_code == 'NON WAPU') checked @endif>
                                                    <label class="form-check-label" for="non-wapu">NON WAPU</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="tax_code" id="no-npwp" value="No NPWP" @if($data->tax_code == 'No NPWP') checked @endif>
                                                    <label class="form-check-label" for="no-npwp">No NPWP</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="npwp" class="form-label">NPWP<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->npwp}}" type="text" class="form-control" id="npwp" name="npwp" required pattern="\d{2}\.\d{3}\.\d{3}\.\d{1}-\d{3}\.\d{3}">
                                                <br>
                                                <small class="text-danger form-text"><span class="text-danger">*</span> Tidak memiliki NPWP tidak dapat didaftarkan</small>
                                                <div class="invalid-feedback">
                                                    NPWP harus dalam format XX.XXX.XXX.X-XXX.XXX
                                                </div>
                                            </div>

                                            <!-- Payment Control Section -->
                                            <div class="col-md-6 mb-3">
                                                <label for="currency" class="form-label">Currency<span class="text-danger">*</span></label>
                                                <select disabled class="form-control" id="currency" name="currency" required>
                                                    <option value="">Select a currency</option>
                                                    @foreach ($currencyArray as $currency)
                                                        <option value="{{ $currency['code'] }}" @if($data->currency == $currency['code']) selected @endif>
                                                            {{ $currency['name'] }} ({{ $currency['code'] }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="bank_key" class="form-label">Bank Account Number<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->bank_key}}" type="text" class="form-control" id="bank_key" name="bank_key" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="bank_account" class="form-label">Bank Name<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->bank_account}}" type="text" class="form-control" id="bank_account" name="bank_account" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="account_holder" class="form-label">Account Holder<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->account_holder}}" type="text" class="form-control" id="account_holder" name="account_holder">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="bank_region" class="form-label">Bank Region<span class="text-danger">*</span></label>
                                                <input readonly value="{{$data->bank_region}}" type="text" class="form-control" id="bank_region" name="bank_region">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="confirm_with" class="form-label">Confirm With PIC Supplier</label>
                                                <input readonly value="{{$data->confirm_with}}" type="text" class="form-control" id="confirm_with" name="confirm_with" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="confirm_with" class="form-label">Email/ No. Handphone</label>
                                                <input readonly value="{{$data->confirm_info}}" type="text" class="form-control" id="confirm_with" name="email_no_handphone" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input readonly value="{{$data->date}}" type="date" class="form-control" id="date" name="date" required >
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="confirmed_by" class="form-label">Confirm By PIC MKM</label>
                                                <input readonly value="{{$data->confirm_by}}" type="text" class="form-control" id="confirmed_by" name="confirmed_by" required>
                                            </div>

                                            <!-- Accounting Information Section -->
                                            <div class="col-md-6 mb-3">
                                                <label for="recon_account" class="form-label">Recon Account</label>
                                                <input readonly value="{{$data->recon_account}}" type="text" class="form-control" id="recon_account" name="recon_account">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="sort_key" class="form-label">Sort Key</label>
                                                <input readonly value="{{$data->sort_key}}" type="text" class="form-control" id="sort_key" name="sort_key">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cash_management_group" class="form-label">Cash Management Group</label>
                                                <input readonly value="{{$data->cash_management_group}}" type="text" class="form-control" id="cash_management_group" name="cash_management_group">
                                            </div>

                                            <!-- Payment Transaction Section -->
                                            <div class="col-md-6 mb-3">
                                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                                <input readonly value="{{ $data->payment_terms }}" type="text" class="form-control" id="payment_terms" name="payment_terms">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Payment Method<span class="text-danger">*</span></label><br>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" required @if($data->payment_method == 'cash') checked @endif>
                                                    <label class="form-check-label" for="cash">Cash</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer" @if($data->payment_method == 'transfer') checked @endif>
                                                    <label class="form-check-label" for="transfer">Transfer</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="payment_method" id="cheque" value="cheque" @if($data->payment_method == 'cheque') checked @endif>
                                                    <label class="form-check-label" for="cheque">Cheque / Giro</label>
                                                </div>
                                                <br>
                                                <div class="mt-4">
                                                    <label for="payment_block" class="form-label">Payment Block</label>
                                                    <input disabled type="checkbox" class="form-check-input" id="payment_block" name="payment_block" @if($data->payment_block) checked @endif>
                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Withholding Tax</label>
                                                @php
                                                    // Split the withholding tax string by the '|' delimiter
                                                    $withholdingTaxes = isset($data->withholding_tax) ? explode('|', $data->withholding_tax) : [];
                                                @endphp
                                                @foreach($tax as $option)
                                                    <div class="form-check">
                                                        <input disabled class="form-check-input" type="checkbox" name="withholding_tax[]" id="{{ $option->code_format }}" value="{{ $option->name_value }}" @if(in_array($option->name_value, $withholdingTaxes)) checked @endif>
                                                        <label class="form-check-label" for="{{ $option->code_format }}">
                                                            {{ $option->name_value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            {{--  <button type="submit" class="btn btn-success mt-3">Approve</button> --}}
                                        </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Uploaded Files</h3>
                                </div>
                                <div class="card-body">
                                    @if($data->files && is_array($data->files) && count($data->files) > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%">Icon</th>
                                                    <th style="width: 70%">File Name</th>
                                                    <th style="width: 20%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data->files as $file)
                                                    @php
                                                        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            @if(in_array($fileExtension, ['xls', 'xlsx']))
                                                                <i class="fas fa-file-excel fa-2x text-success"></i> <!-- Excel Icon -->
                                                            @elseif(in_array($fileExtension, ['pdf']))
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i> <!-- PDF Icon -->
                                                            @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                <i class="fas fa-file-image fa-2x text-primary"></i> <!-- Image Icon -->
                                                            @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                                <i class="fas fa-file-word fa-2x text-info"></i> <!-- Word Icon -->
                                                            @else
                                                                <i class="fas fa-file-alt fa-2x text-secondary"></i> <!-- Default File Icon -->
                                                            @endif
                                                        </td>
                                                        <td>{{ basename($file) }}</td>
                                                        <td>
                                                            <a href="{{ asset($file) }}" class="btn btn-primary btn-sm" download>
                                                                Download
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No files uploaded.</p>
                                    @endif
                                </div>
                            </div>




                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title m-0">Log Form</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Display Vendor Master Details -->
                                    <h2>{{ $data->vendor_name }}</h2>
                                    <p>Supplier Account Number: {{ $data->vendor_account_number }}</p>
                                    <!-- Display other details as needed -->

                                    <!-- Display Latest Change Details -->
                                    @if ($data->latestChange)
                                        <h3>Latest Change Details</h3>
                                        <p>Change Type: {{ $data->latestChange->change_type }}</p>
                                        <!-- Display other details from latest change -->
                                    @endif

                                    <!-- Display Logs -->
                                    @if ($data->latestChange && $data->latestChange->logs->isNotEmpty())
                                        <h3>Logs</h3>
                                        <table id="tableUser" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Approver</th>
                                                    <th>Action</th>
                                                    <th>Comments</th>
                                                    <th>Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data->latestChange->logs as $log)
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
                                                        <td> {{ date('j F Y, H:i', strtotime($log->approval_timestamp)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No logs found for this Supplier master.</p>
                                    @endif
                                </div>
                            </div>

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
            "order": [], // Disable initial sorting
            "columnDefs": [
                { "targets": 'no-sort', "orderable": false } // Disable sorting on specific columns
            ]
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const npwpInput = document.getElementById('npwp');

        npwpInput.addEventListener('input', function () {
            let value = npwpInput.value.replace(/\D/g, ''); // Remove non-digit characters
            let formattedValue = '';

            // Add the format XX.XXX.XXX.X-XXX.XXX
            if (value.length > 2) {
                formattedValue += value.substring(0, 2) + '.';
                value = value.substring(2);
            }
            if (value.length > 3) {
                formattedValue += value.substring(0, 3) + '.';
                value = value.substring(3);
            }
            if (value.length > 3) {
                formattedValue += value.substring(0, 3) + '.';
                value = value.substring(3);
            }
            if (value.length > 1) {
                formattedValue += value.substring(0, 1) + '-';
                value = value.substring(1);
            }
            if (value.length > 3) {
                formattedValue += value.substring(0, 3) + '.';
                value = value.substring(3);
            }
            if (value.length > 3) {
                formattedValue += value.substring(0, 3);
                value = value.substring(3);
            } else {
                formattedValue += value;
            }

            npwpInput.value = formattedValue;
        });
    });
</script>

@endsection
