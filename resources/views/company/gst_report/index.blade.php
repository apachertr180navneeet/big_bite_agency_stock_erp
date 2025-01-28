@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">GST CALCULATION</span>
            </h5>
        </div>
    </div>
    @php
        use Carbon\Carbon;
        $startDate = $startDate ? Carbon::parse($startDate)->format('d-m-Y') : null;
        $endDate = $endDate ? Carbon::parse($endDate)->format('d-m-Y') : null;
    @endphp
    <style>
        / Print CSS /
        @media print {
            @page {
                size: A4 landscape; / Set page size to A4 and orientation to landscape /
                margin: 0; / Optional: Adjust margins as needed /
            }

            body {
                margin: 0; / Remove default margins /
                overflow: hidden; / Prevent scrolling in print mode /
            }

            #printThis {
                overflow: visible; / Allow content to display fully /
            }

            / Hide unnecessary elements for print /
            .no-print, .btn, #filterBtn {
                display: none !important;
            }
        }
    </style>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('company.gst.report.filter') }}" method="GET">
                        <div class="row">
                            <!-- Date Range Filters -->
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request()->get('start_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request()->get('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary mt-4">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('company.purches.report.index') }}" id="reportR1" class="btn btn-primary mt-4">R1</a>
                                <a href="{{ route('company.sales.report.index') }}" id="reportR2" class="btn btn-primary mt-4">R2</a>
                                <button id="printdiv" class="btn btn-primary mt-4"> Print Data</button>
                            </div>
                        </div>
                    </form>

                    <div id="printThis">
                        <!-- Header for Print with Dynamic Data -->
                        <div id="printHeader" class="text-center mb-4">
                            <h4 id="companyName"></h4>
                            <h5 id="gstNumber"></h5>
                            <p id="dateRange"></p>
                        </div>

                        <div class="table-responsive text-nowrap mt-4">
                            <table class="table table-bordered" id="variationTable">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Particular</th>
                                        <th style="width: 10%;">Voucher Count</th>
                                        <th style="width: 10%;">Taxable</th>
                                        <th style="width: 10%;">IGST</th>
                                        <th style="width: 10%;">CGST</th>
                                        <th style="width: 10%;">SGST</th>
                                        <th style="width: 10%;">Cess</th>
                                        <th style="width: 10%;">Tax</th>
                                        <th style="width: 15%;">Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B2B invoices -4A,4B,4C,6B,6C</td>
                                        <td>{{ $b2btotalInvoiceCount }}</td>
                                        <td>{{ $b2btotalAmountBeforeTax }}</td>
                                        <td>{{ $b2btotalIgst }}</td>
                                        <td>{{ $b2btotalSgst }}</td>
                                        <td>{{ $b2btotalCgst }}</td>
                                        <td>0</td>
                                        <td>{{ $b2btotalIgst + $b2btotalSgst + $b2btotalCgst }}</td>
                                        <td>{{ $b2btotalGrandTotal }}</td>
                                    </tr>
                                    <tr>
                                        <td>B2C ( large Invoices )-5A,5B</td>
                                        <td>{{ $b2ctotalInvoiceCount }}</td>
                                        <td>{{ $b2ctotalAmountBeforeTax }}</td>
                                        <td>{{ $b2ctotalIgst }}</td>
                                        <td>{{ $b2ctotalSgst }}</td>
                                        <td>{{ $b2ctotalCgst }}</td>
                                        <td>0</td>
                                        <td>{{ $b2ctotalIgst + $b2ctotalSgst + $b2ctotalCgst }}</td>
                                        <td>{{ $b2ctotalGrandTotal }}</td>
                                    </tr>
                                    <tr>
                                        <td>Credit / Debit Note ( Reg)-9B</td>
                                        <td>{{ $b2bSalesBookItemInvoiceCount }}</td>
                                        <td>{{ $b2bTotalPrice }}</td>
                                        <td>{{ $b2bTotalTaxValue }}</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>{{ $b2bTotalTaxValue }}</td>
                                        <td>{{ $b2bTotalPrice + $b2bTotalTaxValue }}</td>
                                    </tr>
                                    <tr>
                                        <td>Credit/ debit note (unreg)-9B</td>
                                        <td>{{ $b2cSalesBookItemInvoiceCount }}</td>
                                        <td>{{ $b2cTotalPrice }}</td>
                                        <td>{{ $b2cTotalTaxValue }}</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>{{ $b2cTotalTaxValue }}</td>
                                        <td>{{ $b2cTotalPrice + $b2cTotalTaxValue }}</td>
                                    </tr>
                                    <tr id="total">
                                        <td>TOTAL</td>
                                        <td>{{ $b2btotalInvoiceCount + $b2ctotalInvoiceCount + $b2bSalesBookItemInvoiceCount + $b2cSalesBookItemInvoiceCount }}</td>
                                        <td>{{ $b2btotalAmountBeforeTax + $b2ctotalAmountBeforeTax + $b2bTotalPrice + $b2cTotalPrice }}</td>
                                        <td>{{ $b2btotalIgst + $b2ctotalIgst + $b2bTotalTaxValue + $b2cTotalTaxValue }}</td>
                                        <td>{{ $b2btotalSgst + $b2ctotalSgst }}</td>
                                        <td>{{ $b2btotalCgst + $b2ctotalCgst }}</td>
                                        <td>0</td>
                                        <td>{{ ($b2btotalIgst + $b2btotalSgst + $b2btotalCgst) + ($b2ctotalIgst + $b2ctotalSgst + $b2ctotalCgst) + $b2bTotalTaxValue + $b2cTotalTaxValue }}</td>
                                        <td>{{ $b2btotalGrandTotal + $b2ctotalGrandTotal + ($b2bTotalPrice + $b2bTotalTaxValue) + ($b2cTotalPrice + $b2cTotalTaxValue) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Elements for date inputs and buttons
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const reportR1 = document.getElementById('reportR1');
        const reportR2 = document.getElementById('reportR2');

        // Function to update URLs with selected dates
        function updateReportUrls() {
            const start = startDate.value;
            const end = endDate.value;


            const r1Url = new URL(reportR1.href);
            const r2Url = new URL(reportR2.href);

            if (start) r1Url.searchParams.set('start_date', start);
            if (end) r1Url.searchParams.set('end_date', end);

            if (start) r2Url.searchParams.set('start_date', start);
            if (end) r2Url.searchParams.set('end_date', end);

            reportR1.href = r1Url.toString();
            reportR2.href = r2Url.toString();
        }

        // Update URLs when date inputs change
        startDate.addEventListener('change', updateReportUrls);
        endDate.addEventListener('change', updateReportUrls);
    });
</script>
@endsection
