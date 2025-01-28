@extends('company.layouts.app')
@section('style')
@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2"><span class="text-primary fw-light">Sales Invoice</span></h5>
        </div>
        <div class="col-md-6 text-end">
            <button id="print" class="btn btn-primary">Print</button>
        </div>
    </div>
    <div class="row invoice-preview" id="printdata">
        <div id="printHeader" class="text-center mb-4">
            <h4 id="companyName"></h4>
            <h5 id="companyAddress"></h5>
            <h5 id="companyPhone"></h5>
            <h5 id="gstNumber"></h5>
        </div>
        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <h4 class="py-2 mb-2"><span>Sales Invoice</span></h4>
                            <div class="d-flex svg-illustration mb-3 gap-2">
                                <span class="app-brand-logo demo">Logo</span>
                            </div>
                            <p class="mb-1" style="color: #000">Bill To :- {{ $salesReport->customer_name }}</p>
                            <p class="mb-1">Address</p>
                            <p class="mb-0" style="color: #000">{{ $salesReport->customer_address }}</p>
                            <p class="mb-0" style="color: #000">{{ $salesReport->customer_city }} {{ $salesReport->customer_state }} ({{ $salesReport->customer_phone }})</p>
                        </div>
                        <div>
                            <h4 style="color: #000">Invoice #{{ $salesReport->dispatch_number }}</h4>
                            <div class="me-1">
                                <span class="me-1" style="color: #000">Date:</span>
                                <span class="fw-medium" style="color: #000">{{ $salesReport->date }}</span>
                            </div>
                            <div>
                                <span class="me-1" style="color: #000">GST No.:</span>
                                <span class="fw-medium" style="color: #000">{{ $salesReport->customer_gst_no }}</span><br>
                                <span class="fw-medium" style="color: #000">Transport Mode :- {{ $salesReport->item_weight }}</span><br>
                                <span class="fw-medium" style="color: #000">Place Of Supply :- </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table border-top m-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>HSN</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Tax</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesReport->salesbookitem as $index => $item)
                                <tr>
                                    <td class="text-nowrap" style="color: #000">{{ $index + 1 }}</td>
                                    <td class="text-nowrap" style="color: #000">{{ $item->item->name }}</td>
                                    <td class="text-nowrap" style="color: #000">{{ $item->item->hsn_hac }}</td>
                                    <td class="text-nowrap" style="color: #000">{{ $item->quantity ?? 'N/A' }}</td>
                                    <td class="text-nowrap" style="color: #000">₹{{ number_format(floatval($item->rate ?? 0), 2) }}</td>
                                    <td class="text-nowrap" style="color: #000">{{ $item->item->tax->rate }} %</td>
                                    <td class="text-nowrap" style="color: #000">₹{{ number_format(floatval($item->amount ?? 0), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="px-4 py-5">
                                    <span style="color: #000">{{ $grandtotalwrod }}</span><br>
                                    <span style="color: #000">Bank :</span><br>
                                    <span style="color: #000">Account Name :- {{ $bank->name }}</span><br>
                                    <span style="color: #000">Account Number :- {{ $bank->account_number }}</span><br>
                                    <span style="color: #000">IFSC Code :- {{ $bank->ifsc_code }}</span><br>
                                    <span style="color: #000">Bank Name :- {{ $bank->bank_name }}</span><br>
                                    <h6 class="mb-2 mt-4" style="color: #000">Terms and Conditions</h6>
                                    <ul>
                                        <li style="color: #000">Interest @ 24% will be payable if this bill is not paid within 1 month</li>
                                        <li style="color: #000">No claim for leakage, shortage, or theft in transit</li>
                                        <li style="color: #000">Our responsibility ceases once the material leaves our premises</li>
                                        <li style="color: #000">Products are for industrial use only</li>
                                        <li style="color: #000">Computer-generated invoice</li>
                                    </ul>
                                </td>
                                <td class="text-end px-4 py-5">
                                    <p class="mb-2" style="color: #000">SubTotal :</p>
                                    <p class="mb-2" style="color: #000">Other Exp.:</p>
                                    <p class="mb-2" style="color: #000">Discount:</p>
                                    <p class="mb-2" style="color: #000">Tax(IGST):</p>
                                    <p class="mb-2" style="color: #000">Tax(SGST):</p>
                                    <p class="mb-2" style="color: #000">Tax(CGST):</p>
                                    <p class="mb-2" style="color: #000">Round Off:</p>
                                    <p class="mb-0" style="color: #000">Total:</p>
                                    <p class="mb-0" style="color: #000">Received :</p>
                                    <p class="mb-0" style="color: #000">Balance :</p>
                                </td>
                                <td class="text-end px-4 py-5">
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->amount_before_tax ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->other_expense ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->discount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->igst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->sgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->cgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2" style="color: #000">₹{{ number_format(floatval($salesReport->round_off ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0" style="color: #000">₹{{ number_format(floatval($salesReport->grand_total ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0" style="color: #000">₹{{ number_format(floatval($salesReport->recived_amount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0" style="color: #000">₹{{ number_format(floatval($salesReport->balance_amount ?? 0), 2) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('#print').on('click', function() {
            $('#companyName').text("{{ $companyDetail->name }}");
            $('#gstNumber').text("GSTIN: {{ $companyDetail->gstin }}");
            $('#companyAddress').text("{{ $companyDetail->address }}");
            $('#companyPhone').text("{{ $companyDetail->phone }}");
            var printContents = $('#printdata').html();
            $('body').html(printContents);
            window.print();
            location.reload();
        });
    });
</script>
@endsection
