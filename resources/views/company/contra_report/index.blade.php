@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Contra Report</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button id="printdiv" class="btn btn-primary mt-4"> Print Data</button>
        </div>
    </div>
    <style>
        /* Print CSS */
        @media print {
            @page {
                size: A4 landscape; /* Set page size to A4 and orientation to landscape */
                margin: 0; /* Optional: Adjust margins as needed */
            }

            body {
                margin: 0; /* Remove default margins */
                overflow: hidden; /* Prevent scrolling in print mode */
            }

            #printThis {
                overflow: visible; /* Allow content to display fully */
            }

            /* Hide unnecessary elements for print */
            .no-print, .btn, #filterBtn {
                display: none !important;
            }
        }
    </style>
    <div class="row" id="printThis">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header for Print with Dynamic Data -->
                    <div id="printHeader" class="text-center mb-4">
                        <h4 id="companyName"></h4>
                        <h5 id="gstNumber"></h5>
                        <p id="dateRange"></p>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="variationTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Serial No.</th>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                    <th>Deposit in</th>
                                    <th>Withdraw  From</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $bankandcashs as $bankandcash )
                                    <tr>
                                        <td>{{ $bankandcash->date }}</td>
                                        <td>{{ $bankandcash->serial_no }}</td>
                                        <td>{{ $bankandcash->particular }}</td>
                                        <td>{{ $bankandcash->amount }}</td>
                                        <td>{{ $bankandcash->deposite_bank_name }}</td>
                                        <td>{{ $bankandcash->withdraw_bank_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{--  <div class="row">
                        <div class="col-md-3">
                            <h4>Net Value = <span id="netValue"></span></h4>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <h4>Deposit = <span id="DepositValue"></span></h4>
                        </div>
                        <div class="col-md-3">
                            <h4>Withdraw Value = <span id="WithdrawValue"></span></h4>
                        </div>
                    </div>  --}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#printdiv').click(function() {
            // Set header details dynamically
            $('#companyName').text("{{ $companyDetail->name }}");
            $('#gstNumber').text("GSTIN: {{ $companyDetail->gstin }}");
            $('#dateRange').text("Date Range: {{ $startDate }} - {{ $endDate }}");

            // Prepare for printing
            var printContents = $('#printThis').html();
            var originalContents = $('body').html();

            $('body').html(printContents);
            window.print();
            $('body').html(originalContents);

            // Reload the page after print dialog is closed
            window.location.reload();
        });
    });
</script>
@endsection
