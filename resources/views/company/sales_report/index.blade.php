@extends('company.layouts.app')
@section('style')

@endsection
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Sales Report</span>
            </h5>
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
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Date Range Filters -->
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button id="filterBtn" class="btn btn-primary mt-4">Filter</button>
                            <button id="printdiv" class="btn btn-primary mt-4"> Print Data</button>
                        </div>
                    </div>
                    <div id="printThis">
                        <!-- Header for Print with Dynamic Data -->
                        <div id="printHeader" class="text-center mb-4">
                            <h4 id="companyName"></h4>
                            <h5 id="gstNumber"></h5>
                            <p id="dateRange"></p>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" id="variationTable" style="width: 99%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice number</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
        // Base URL for the edit route
        const baseUrl = "{{ route('company.sales.report.print', ['id' => ':id']) }}";

        function getSearchParam(param){
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const startDate = getSearchParam('start_date');
        const endDate = getSearchParam('end_date');

        if (startDate) {
            $('#start_date').val(startDate);
        }
        if (endDate) {
            $('#end_date').val(endDate);
        }

        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.sales.report.getall') }}",
                type: 'GET',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
            },
            columns: [
                {
                    data: "date",

                },
                { data: "dispatch_number" },
                { data: "customer_name" },
                { data: "grand_total" },
                {
                    data: "id",
                    render: function (data, type, row) {
                        const printButton = `<a href="${baseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Print</a>`;
                        return `${printButton}`;
                    },
                },
            ],
        });

        if (startDate || endDate) {
            table.ajax.reload();
        }
        // Filter Button Click Event
        $('#filterBtn').click(function() {
            table.ajax.reload(); // Reload the DataTable with the new date range filter
        });

    });
</script>
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
