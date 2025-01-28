@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Stock Report</span>
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
            .no-print, .btn, #filterBtn,
            .dataTables_filter, /* Hides search */
            .dataTables_info, /* Hides row info */
            .dataTables_paginate, /* Hides pagination */
            .dataTables_length /* Hides entries count selection */ {
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="variationTable" style="width: 99%">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Current Stock</th>
                                    <th>Sales</th>
                                    <th>S Return</th>
                                    <th>Purches</th>
                                    <th>P resturn</th>
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

@endsection
@section('script')
<script>
    $(document).ready(function() {
        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.stock.report.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "name" },
                {
                    data: "total_stock_quantity",
                    render: function(data) {
                        return data || 0; // Returns 0 if data is null or undefined
                    }
                },
                {
                    data: null, // Use null for custom data rendering
                    render: function(row) {
                        const totalSalesQty = row.total_sales_book_qty || 0;
                        const totalReturnQty = row.total_sreturn || 0;
                        return totalSalesQty - totalReturnQty; // Calculate and return the value
                    }
                },
                {
                    data: "total_sreturn",
                    render: function(data) {
                        return data || 0; // Returns 0 if data is null or undefined
                    }
                },
                {
                    data: null, // Use null for custom data rendering
                    render: function(row) {
                        const totalPurchasesQty = row.total_purches_book_qty || 0;
                        const totalPReturnQty = row.total_preturn || 0;
                        return totalPurchasesQty - totalPReturnQty; // Calculate and return the value
                    }
                },
                {
                    data: null,
                    render: function(row) {
                        const totalPReturnQty = row.total_preturn || 0;
                        return totalPReturnQty; // Calculate and return the value
                    }
                },
            ]
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
