@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Purches Invoice</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button id="print" class="btn btn-primary">
                Print
            </button>
        </div>
    </div>
    <div class="row invoice-preview" id="printdata">
        <!-- Invoice -->
        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <h4 class="py-2 mb-2">
                                <span>Purchase Invoice</span>
                            </h4>
                            <div class="d-flex svg-illustration mb-3 gap-2">
                                <span class="app-brand-logo demo">
                                    Logo
                                </span>
                            </div>
                            <p class="mb-1">Company Name :- {{ $subCompany->name  }} </p>
                            <p class="mb-1">Bill From :- {{ $purchaseReport->vendor_name  }} </p>
                            <p class="mb-1">Address</p>
                            <p class="mb-0">{{ $purchaseReport->vendor_address }} {{ $purchaseReport->vendor_city }} {{ $purchaseReport->vendor_state }} ({{ $purchaseReport->vendor_phone }})</p>
                        </div>
                        <div>
                            <h4>Invoice #{{ $purchaseReport->invoice_number }}</h4>
                            <div class="me-1">
                                <span class="me-1">Date:</span>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($purchaseReport->date)->format('d-m-Y') }}</span>
                            </div>
                            <div>
                                {{--  <span class="me-1">GST No.:</span>
                                <span class="fw-medium">{{ $purchaseReport->vendor_gst_no }}</span>  --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table border-top m-0">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>HSN</th>
                                <th>Variation</th>
                                <th>Price</th>
                                <th>Tax</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseReport->purchesbookitem as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item->name }}</td>
                                <td>{{ $item->quantity ?? 'N/A' }}</td>
                                <td>{{ $item->item->hsn_hac }}</td>
                                <td>{{ $item->item->variation->name }}</td>
                                <td>₹{{ $item->item->tax->rate }}</td>
                                <td>₹{{ number_format(floatval($item->tax ?? 0), 2) }}</td>
                                <td>₹{{ number_format(floatval($item->amount ?? 0), 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="align-top px-4 py-5">
                                    <span>{{ $grandtotalwrod}}</span>
                                </td>
                                <td colspan="2" class="text-end px-4 py-5">
                                    <p class="mb-2">SUBTOTAL :</p>
                                    <p class="mb-2">Other Expenses(+):</p>
                                    <p class="mb-2">Discount(-):</p>
                                    <p class="mb-2">Tax(IGST)(+):</p>
                                    <p class="mb-2">Tax(SGST)(+):</p>
                                    <p class="mb-2">Tax(CGST)(+):</p>
                                    <p class="mb-2">Round Off(-/+):</p>
                                    <p class="mb-0">Grand Total:</p>
                                    <p class="mb-0">Given Amount:</p>
                                    <p class="mb-0">Remaining Balance :</p>
                                </td>
                                <td colspan="2" class="px-4 py-5">
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->amount_before_tax ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->other_expense ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->discount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->igst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->sgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->cgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($purchaseReport->round_off ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($purchaseReport->grand_total ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($purchaseReport->given_amount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($purchaseReport->remaining_blance ?? 0), 2) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Invoice -->
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('#print').on('click', function() {
            var printContents = $('#printdata').html();
            var originalContents = $('body').html(); // Store original content

            // Replace the body content with print contents
            $('body').html(printContents);

            // Trigger the print dialog
            window.print();

            // Restore the original content
            $('body').html(originalContents);
        });
    });
</script>

@endsection
