@extends('company.layouts.app')

@section('style')
    <!-- Add any necessary styles here -->
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">View</span>
                </h5>
            </div>
        </div>
        <form role="form" action="{{ route('company.sales.book.update', $salesBook->id) }}" method="post" id="Sales_edit"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card mb-4">
                        <h5 class="card-header">View</h5>
                        <div class="card-body">
                            <!-- Purchase details form -->
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="text" id="date" name="date" value="{{ $salesBook->date }}" readonly>
                                </div>
                                <!-- dispatch Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dispatch" class="form-label">Dispatch</label>
                                    <input type="text" class="form-control" id="dispatch" name="dispatch"
                                        value="{{ $salesBook->dispatch_number }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Sub Company</label>
                                    <input type="text" class="form-control" id="weight" name="weight"
                                        value="{{ $subCompany->name }}" readonly>
                                </div>
                                <!-- customer Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="customer" class="form-label">Costomer</label>
                                    <select class="form-select" id="customer" name="customer" disabled>
                                        <option selected>Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $salesBook->customer_id ? 'selected' : '' }}>
                                                {{ $customer->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Items Table -->

                            <table class="table table-bordered mt-4" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Return</th>
                                        <th>Variation</th>
                                        <th>HSN</th>
                                        <th>Rate</th>
                                        <th>Tax</th>
                                        <th>Total Amount</th>
                                        {{--  <th>Action</th>  --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Pre-fill items from the purchase book -->
                                    @foreach ($salesBook->salesbookitem as $index => $item)
                                        @php
                                            $tax_rate = ($item->tax * 100) / $item->amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->item->name }}<input type="hidden" name="items[]"
                                                    value="{{ $item->item_id }}"></td>
                                            <td>{{ $item->quantity ?? 'N/A' }}<input type="hidden" name="quantities[]"
                                                    value="{{ $item->quantity }}"></td>
                                            <td>{{ $item->sreturn ?? 'N/A' }}<input type="hidden" name="sreturn[]"
                                                    value="{{ $item->sreturn }}"></td>
                                            <td>{{ $item->item->variation->name }}</td>
                                            <td>{{ $item->item->hsn_hac }}</td>
                                            <td>{{ number_format(floatval($item->rate ?? 0), 2) }}<input type="hidden"
                                                    name="rates[]"
                                                    value="{{ number_format(floatval($item->rate ?? 0), 2) }}"></td>
                                            <td>{{ number_format(floatval($tax_rate ?? 0), 2) }}%<input type="hidden"
                                                    name="taxes[]"
                                                    value="{{ number_format(floatval($item->tax ?? 0), 2) }}"></td>
                                            <td>{{ number_format(floatval($item->amount ?? 0), 2) }}<input type="hidden"
                                                    name="totalAmounts[]"
                                                    value="{{ number_format(floatval($item->amount ?? 0), 2) }}"></td>
                                            {{--  <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>  --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary fields -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="amount_before_tax" class="form-label text-end">Amount Before Tax</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="amount_before_tax"
                                        value="{{ number_format((float) $salesBook->amount_before_tax, 2) }}"
                                        name="amount_before_tax" min="0" readonly>
                                    @error('amount_before_tax')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- IGST Tax -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="igst" class="form-label text-end">IGST</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="igst"
                                        value="{{ number_format((float) $salesBook->igst, 2) }}" name="igst"
                                        min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- CGST/SGST Tax -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="igst" class="form-label text-end">CGST/SGST</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-2 mb-3">
                                    <input type="text" class="form-control" id="cgst"
                                        value="{{ number_format((float) $salesBook->cgst, 2) }}" name="cgst"
                                        min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="text" class="form-control" id="sgst"
                                        value="{{ number_format((float) $salesBook->sgst, 2) }}" name="sgst"
                                        min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Other Expenses -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="other_expense" class="form-label text-end">Other Expense(+)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="other_expense"
                                        value="{{ number_format((float) $salesBook->other_expense, 2) }}" min="0"
                                        name="other_expense" readonly>
                                </div>
                            </div>
                            <!-- Discount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="discount" class="form-label text-end">Discount(-)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="discount" name="discount"
                                        min="0" value="{{ number_format((float) $salesBook->discount, 2) }}" readonly>
                                </div>
                            </div>
                            <!-- Round Off -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="round_off" class="form-label text-end">Round Off(-/+)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="round_off" name="round_off"
                                        value="{{ number_format((float) $salesBook->round_off, 2) }}" step="any" readonly>
                                </div>
                            </div>
                            <!-- Grand Total -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="grand_total" class="form-label text-end">Grand Total</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="grand_total" name="grand_total"
                                        value="{{ number_format((float) $salesBook->grand_total, 2) }}" min="0"
                                        readonly>
                                </div>
                            </div>
                            <!-- Given Amount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="received_amount" class="form-label text-end">Received Amount</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="received_amount"
                                        name="received_amount"
                                        value="{{ number_format((float) $salesBook->recived_amount, 2) }}"
                                        min="0" readonly>
                                    @error('received_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Remaining Balance -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="balance_amount" class="form-label text-end">Balance Amount</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="balance_amount" name="balance_amount"
                                        value="{{ number_format((float) $salesBook->balance_amount, 2) }}" min="0"
                                        readonly>
                                    @error('balance_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    
@endsection
