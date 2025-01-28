@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Bank And Cash Report</span>
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
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="variationTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Particular</th>
                                    <th>in</th>
                                    <th>Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $combinedRecords as $combinedRecord )
                                    <tr>
                                        <td>{{ $combinedRecord->date }}</td>
                                        <td>{{ $combinedRecord->payment_type }}</td>
                                        <td>{{ $combinedRecord->name }}</td>
                                        @if($combinedRecord->type == 'receipt')
                                            <td>{{ $combinedRecord->amount }}</td>
                                        @else
                                            <td>-</td>
                                        @endif

                                        @if($combinedRecord->type == 'payment')
                                            <td>{{ $combinedRecord->amount }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <h4>Recipt Value = <span id="WithdrawValue">{{ $totalReceipt }}</span></h4>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <h4>Payment = <span id="DepositValue">{{ $totalPayment }}</span></h4>
                        </div>
                        <div class="col-md-2">
                            <h4>Net Value = <span id="netValue">{{ $totalReceipt - $totalPayment }}</span></h4>
                        </div>
                    </div>
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
