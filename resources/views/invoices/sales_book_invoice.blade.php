<style>
    .table-container {
        width: 100%;
    }

    .table {
        table-layout: auto;
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        word-wrap: break-word;
        height: 50px;
        padding: 10px;
        color: #000;
    }

    .table th {
        text-align: left;
    }

    .table td {
        border: none;
    }

    .table th {
        border-bottom: 2px solid #000;
    }
</style>
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row invoice-preview" id="printdata">
        <div id="printHeader" class="row">
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-start">
                    <img src="{{ $companyDetail->logo }}" alt="" id="logoImage" width="100" height="100" class="d-none me-3">
                </div>
                <div class="text-center flex-grow-1">
                    <h4>Tax Invoice</h4>
                    <h4 id="companyName" class="mb-0">{{ $companyDetail->name }}</h4>
                    <h5 id="companyAddress" class="mb-0">{{ $companyDetail->address }}</h5>
                    <h5 id="companyPhone" class="mb-0">{{ $companyDetail->phone }}</h5>
                    <h5 id="gstNumber" class="mb-0">{{ $companyDetail->gstin }}</h5>
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
                            <p class="mb-0" style="color: #000">{{ $salesReport->customer_state }}</p>
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
                                <th class="fw-bolder" style="font-size: 17px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesReport->salesbookitem as $index => $item)
                                <tr>
                                    <td class="text-nowrap">{{ $index + 1 }}</td>
                                    <td class="text-nowrap">{{ $item->item->name }}</td>
                                    <td class="text-nowrap">{{ $item->item->hsn_hac }}</td>
                                    <td class="text-nowrap">{{ $item->quantity ?? 'N/A' }}</td>
                                    <td class="text-nowrap">₹{{ number_format(floatval($item->rate ?? 0), 2) }}</td>
                                    <td class="text-nowrap">{{ $item->item->tax->rate }} %</td>
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
                                <td class="text-end">
                                    <p class="mb-1" style="color: #000">SubTotal :</p>
                                    <p class="mb-1" style="color: #000">Other Exp.:</p>
                                    <p class="mb-1" style="color: #000">Discount:</p>
                                    <p class="mb-1" style="color: #000">Tax(IGST):</p>
                                    <p class="mb-1" style="color: #000">Tax(SGST / CGST):</p>
                                    <p class="mb-1" style="color: #000">Round Off:</p>
                                    <p class="mb-0" style="color: #000">Total:</p>
                                    <p class="mb-0" style="color: #000">Received :</p>
                                    <p class="mb-0" style="color: #000">Balance :</p>
                                </td>
                                <td class="text-end">
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->amount_before_tax ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->other_expense ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->discount ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->igst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->sgst ?? 0), 2) }} / ₹{{ number_format(floatval($salesReport->cgst ?? 0), 2) }}</p>
                                    <p class="fw-medium mb-1" style="color: #000">₹{{ number_format(floatval($salesReport->round_off ?? 0), 2) }}</p>
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

        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4 text-end">
            <p>For : {{ $companyDetail->name }}</p>
            <p class="mb-4">Authorized Signatory</p>
        </div>
    </div>
</div>

