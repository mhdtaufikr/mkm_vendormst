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
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Customer Master Detail</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!-- Alert Messages -->
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

                                    <!-- Customer Details -->
                                    <h3><strong>Customer Details</strong></h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="customer_account_number" class="form-label">Customer Account Number</label>
                                            <input readonly value="{{ $data->customer_account_number }}" type="text" class="form-control" id="customer_account_number" name="customer_account_number">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Account Group</label><br>
                                            <div class="row">
                                                @foreach($customerAG as $index => $group)
                                                    <div class="col-md-6">
                                                        <div class="form-check form-check-inline">
                                                            <input disabled class="form-check-input" type="checkbox" name="account_group[]" id="account_group_{{ $index }}" value="{{ $group->name_value }}" @if(in_array($group->name_value, $data->account_group)) checked @endif>
                                                            <label class="form-check-label" for="account_group_{{ $index }}">{{ $group->name_value }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Change Type</label><br>
                                            @foreach($types as $type)
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="change_type" id="{{ $type->name_value }}" value="{{ $type->name_value }}" @if($data->latestChange->change_type == $type->name_value) checked @endif>
                                                    <label class="form-check-label" for="{{ $type->name_value }}">{{ $type->name_value }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="previous_sap_customer_number" class="form-label">Previous SAP Customer Number</label>
                                            <input readonly value="{{ $data->latestChange->previous_sap_customer_number }}" type="text" class="form-control" id="previous_sap_customer_number" name="previous_sap_customernumber">
                                        </div>
                                    </div>

                                    <!-- Reference Information -->
                                    <h3><strong>Reference Information</strong></h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_code" class="form-label">Company Code</label>
                                            <input readonly value="3000" type="text" class="form-control" id="company_code" name="company_code">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="customer_name" class="form-label">Customer Name</label>
                                            <input readonly value="{{ $data->customer_name }}" type="text" class="form-control" id="customer_name" name="customer_name">
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <h3><strong>Address Information</strong></h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Title</label><br>
                                            @foreach($title as $item)
                                                <div class="form-check form-check-inline">
                                                    <input disabled class="form-check-input" type="radio" name="title" id="{{ $item->name_value }}" value="{{ $item->name_value }}" @if($data->title == $item->name_value) checked @endif>
                                                    <label class="form-check-label" for="{{ $item->name_value }}">{{ $item->name_value }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input readonly value="{{ $data->name }}" type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="search_term_1" class="form-label">Search Term 1</label>
                                            <input readonly value="{{ $data->search_term_1 }}" type="text" class="form-control" id="search_term_1" name="search_term_1">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="search_term_2" class="form-label">Search Term 2</label>
                                            <input readonly value="{{ $data->search_term_2 }}" type="text" class="form-control" id="search_term_2" name="search_term_2">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <select disabled class="form-control" id="country" name="country">
                                                <option value="">Select a country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['cca3'] }}" @if($data->country == $country['cca3']) selected @endif>{{ $country['name']['common'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="region" class="form-label">Region</label>
                                            <input readonly value="{{ $data->region }}" type="text" class="form-control" id="region" name="region">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input readonly value="{{ $data->city }}" type="text" class="form-control" id="city" name="city">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="street" class="form-label">Street</label>
                                            <input readonly value="{{ $data->street }}" type="text" class="form-control" id="street" name="street">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="house_number" class="form-label">Home No.</label>
                                            <input readonly value="{{ $data->house_number }}" type="text" class="form-control" id="house_number" name="house_number">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="postal_code" class="form-label">Postal Code</label>
                                            <input readonly value="{{ $data->postal_code }}" type="text" class="form-control" id="postal_code" name="postal_code">
                                        </div>
                                        <div class="col-md-1 mb-3">
                                            <label for="po_box" class="form-label">P.O. Box</label>
                                            <input readonly value="{{ $data->po_box }}" type="text" class="form-control" id="po_box" name="po_box">
                                        </div>
                                        <div class="col-md-1 mb-3">
                                            <label for="fax" class="form-label">Fax</label>
                                            <input readonly value="{{ $data->fax }}" type="text" class="form-control" id="fax" name="fax">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="telephone" class="form-label">Telephone</label>
                                            <input readonly value="{{ $data->telephone }}" type="text" class="form-control" id="telephone" name="telephone">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input readonly value="{{ $data->email }}" type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>

                                    <!-- Control Information -->
                                    <h3><strong>Control Information</strong></h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tax Code</label><br>
                                            <div class="form-check form-check-inline">
                                                <input disabled class="form-check-input" type="radio" name="tax_code" id="wapu" value="WAPU" @if($data->tax_code == 'WAPU') checked @endif>
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
                                            <label for="npwp" class="form-label">NPWP</label>
                                            <input readonly value="{{ $data->npwp }}" type="text" class="form-control" id="npwp" name="npwp">
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
                                    <label for="bank_key" class="form-label">Bank Account Number</label>
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
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h3 class="card-title">Uploaded Files</h3>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $files = json_decode($data->file); // Decode the JSON string into a PHP array
                                            @endphp
                                            @if($files && is_array($files) && count($files) > 0)
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">Icon</th>
                                                            <th style="width: 70%">File Name</th>
                                                            <th style="width: 20%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($files as $file)
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


                                    <!-- Log Section -->
                                    <h3><strong>Change Log</strong></h3>
                                    @if ($data->latestChange && $data->latestChange->logs->isNotEmpty())
                                    <table class="table table-bordered table-striped">
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
                                                @if($log->approval_action === 'checked') table-success
                                                @elseif($log->approval_action === 'remand') table-danger
                                                @elseif($log->approval_action === 'Submitter') table-primary
                                                @endif">
                                                    <td>{{ $log->approver->name }}</td>
                                                    <td>{{ $log->approval_action }}</td>
                                                    <td>{{ $log->approval_comments }}</td>
                                                    <td>{{ date('j F Y, H:i', strtotime($log->approval_timestamp)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>No logs found for this customer master.</p>
                                    @endif

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
