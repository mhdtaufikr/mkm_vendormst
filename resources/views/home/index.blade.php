@extends('layouts.master')

@section('content')
<style>
    #lblGreetings {
        font-size: 1rem; /* Adjust the base font size as needed */
    }

    @media only screen and (max-width: 600px) {
        #lblGreetings {
            font-size: 1rem; /* Adjust the font size for smaller screens */
        }
    }
    .page-header .page-header-content {
        padding-top: 0rem;
        padding-bottom: 1rem;
    }
</style>

<script src="{{ asset('test.js') }}"></script>
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-2">
                        <h1 class="page-header-title">
                            <label id="lblGreetings"></label>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="content">
        <div class="container-fluid">
            <div class="container-xl px-4 mt-n10">
                <!-- Pending List Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Pending List for Approval</h3>
                    </div>
                    <div class="card-body">
                        @if($pendingList->isEmpty())
                            <p>No pending approvals at this moment.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Form Type</th>
                                        <th>Name</th>
                                        <th>Change Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingList as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->type == 'Supplier' ? $item->vendor->name : $item->customer->name }}</td>
                                            <td>{{ $item->change_type }}</td>
                                            <td>
                                                <a href="{{ $item->type == 'Supplier' ? url('/vendor/checked/' .encrypt($item->vendor_id)) : url('/customer/checked/' .encrypt($item->customer_id)) }}" class="btn btn-primary btn-sm">
                                                    Approve
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var myDate = new Date();
        var hrs = myDate.getHours();
        var greet;

        if (hrs < 12)
            greet = 'Good Morning';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Good Afternoon';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Good Evening';

        document.getElementById('lblGreetings').innerHTML =
            '<b>' + greet + '</b> and welcome to MKM Vendor and Customer Request';
    </script>
</main>
@endsection
