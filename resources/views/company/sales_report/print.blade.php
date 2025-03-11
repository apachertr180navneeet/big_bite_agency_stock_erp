@extends('company.layouts.app')
@section('style')
<style>
    .table-container {
        width: 100%; /* Ensures the container spans the full width */
    }

    .table {
        table-layout: auto; /* Table adjusts its size dynamically */
        width: 100%;
        border-collapse: collapse; /* Removes double borders between table cells */
    }

    .table th, .table td {
        word-wrap: break-word; /* Prevent text overflow */
        height: 50px; /* Ensures consistent row height */
        padding: 10px; /* Adds padding for better readability */
        color: #000; /* Sets uniform text color */
    }

    .table th {
        text-align: left;
    }

    .table td {
        border: none; /* Removes borders from table cells */
    }

    .table th {
        border-bottom: 2px solid #000; /* Adds a bottom border to the header row */
    }
</style>
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
        <div id="printHeader" class="row">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Logo -->
                <div class="text-start">
                    <img src="{{ $companyDetail->logo }}" alt="" id="logoImage" width="100" height="100" class="d-none me-3">
                </div>
                <!-- Company Details -->
                <div class="text-center flex-grow-1">
                    <h4>Tax Invoice</h4>
                    <h4 id="companyName" class="mb-0"></h4>
                    <h5 id="companyAddress" class="mb-0"></h5>
                    <h5 id="companyPhone" class="mb-0"></h5>
                    <h5 id="gstNumber" class="mb-0"></h5>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body m-0 p-0">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <p class="mb-1" style="color: #000">Party Name :- {{ $salesReport->customer_name }}</p>
                            <p class="mb-1">Address</p>
                            <p class="mb-0" style="color: #000">{{ $salesReport->customer_address }}</p>
                            <p class="mb-0" style="color: #000">{{ $salesReport->customer_city }}({{ $salesReport->customer_phone }})</p>
                            <p class="mb-0" style="color: #000">
                                {{ $salesReport->customer_state }}
                                @if ($salesReport->customer_gst_no) 
                                    (State Code :- {{ substr($salesReport->customer_gst_no, 0, 2) }})
                                @endif
                            </p>
                            @if ($salesReport->customer_gst_no)
                                <p class="mb-0" style="color: #000">GST No.: {{ $salesReport->customer_gst_no }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 style="color: #000">Invoice #{{ $salesReport->dispatch_number }}</h4>
                            <h5 style="color: #000">Invoice By {{ $subCompany->name }}</h5>
                            <div class="me-1">
                                <span class="me-1" style="color: #000">Date:</span>
                                <span class="fw-medium" style="color: #000">{{ $salesReport->date }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table border-top m-0">
                        <thead>
                            <tr>
                                <th class="fw-bolder" style="font-size: 17px;">No.</th>
                                <th class="fw-bolder" style="font-size: 17px;">Item</th>
                                <th class="fw-bolder" style="font-size: 17px;">HSN</th>
                                <th class="fw-bolder" style="font-size: 17px;">Qty</th>
                                <th class="fw-bolder" style="font-size: 17px;">Rate</th>
                                <th class="fw-bolder" style="font-size: 17px;">Tax</th>
                                <th class="fw-bolder" style="font-size: 17px;">Cess</th>
                                <th class="fw-bolder" style="font-size: 17px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesReport->salesbookitem as $index => $item)
                                <tr>
                                    <td class="text-nowrap">{{ $index + 1 }}</td>
                                    <td class="text-nowrap">{{ $item->item->name }}</td>
                                    <td class="text-nowrap">{{ $item->item->hsn_hac }}</td>
                                    <td class="text-nowrap">{{ $item->sreturn == 0 ? ($item->quantity ?? 'N/A') : ($item->sreturn ?? 'N/A') }}</td>
                                    <td class="text-nowrap">₹{{ number_format(floatval($item->rate ?? 0), 2) }}</td>
                                    <td class="text-nowrap">{{ $item->tax }}</td>
                                    <td class="text-nowrap">{{ $item->cess }}</td>
                                    <td class="text-nowrap">₹{{ number_format(floatval($item->amount ?? 0), 2) }}</td>
                                </tr>
                            @endforeach

                            <!-- Fill empty rows if less than 5 items -->
                            @for ($i = count($salesReport->salesbookitem); $i < 5; $i++)
                                <tr>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                    <td class="text-nowrap"></td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="5" class="">
                                    <span style="color: #000">Rupees {{ $grandtotalwrod }}</span><br>
                                    @if($bank)
                                        <span style="color: #000">Bank :</span><br>
                                        <span style="color: #000">Account Name :- {{ $bank->name }}</span><br>
                                        <span style="color: #000">Account Number :- {{ $bank->account_number }}</span><br>
                                        <span style="color: #000">IFSC Code :- {{ $bank->ifsc_code }}</span><br>
                                        <span style="color: #000">Bank Name :- {{ $bank->bank_name }}</span><br>
                                    @endif
                                    <h6 class="mb-2 mt-4" style="color: #000">Terms and Conditions</h6>
                                    <ul>
                                        <li style="color: #000; font-size: 12px;">Interest @ 24% will be payable if this bill is not paid within 1 month</li>
                                        <li style="color: #000; font-size: 12px;">No claim for leakage, shortage, or theft in transit</li>
                                        <li style="color: #000; font-size: 12px;">Our responsibility ceases once the material leaves our premises</li>
                                        <li style="color: #000; font-size: 12px;">Products are for industrial use only</li>
                                        <li style="color: #000; font-size: 12px;">Computer-generated invoice</li>
                                    </ul>
                                </td>
                                <td colspan="2" class="text-end px-4 py-5">
                                    <p class="mb-2">SUBTOTAL :</p>
                                    <p class="mb-2">Discount(-):</p>
                                    <p class="mb-2">Discount Value(-):</p>
                                    <p class="mb-2">Other Expenses(+):</p>
                                    <p class="mb-2">Tax(IGST)(+):</p>
                                    <p class="mb-2">Tax(SGST)(+):</p>
                                    <p class="mb-2">Tax(CGST)(+):</p>
                                    <p class="mb-2">Tax(CESS)(+):</p>
                                    <p class="mb-2">Round Off(-/+):</p>
                                    <p class="mb-0">Grand Total:</p>
                                    <p class="mb-0">Given Amount:</p>
                                    <p class="mb-0">Remaining Balance :</p>
                                </td>
                                <td colspan="1" class="px-4 py-5">
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->amount_before_tax ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->discount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->discount_value ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->other_expense ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->igst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->sgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->cgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->cess ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-2">₹{{ number_format(floatval($salesReport->round_off ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($salesReport->grand_total ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($salesReport->recived_amount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-0">₹{{ number_format(floatval($salesReport->balance_amount ?? 0), 2) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4 text-end">
            <p>For : {{ $companyDetail->name }}</p>


            <p class="mb-4">Authorized Signatory</p>
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
            $('#logoImage').attr('src', "{{ $companyDetail->logo }}").removeClass('d-none');

            var printContents = $('#printdata').html();
            $('body').html(printContents);
            window.print();
            location.reload();
        });
    });
</script>
@endsection
