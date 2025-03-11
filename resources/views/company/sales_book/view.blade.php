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
                                        <th>Cess</th>
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
                                            @php
                                                $value = $item->sreturn == 0 ? ($item->quantity ?? 'N/A') : ($item->sreturn ?? 'N/A');
                                            @endphp
                                            <td>{{ $value }}<input type="hidden" name="quantities[]" value="{{ $value }}"></td>                                                
                                            <td>{{ $item->quantity - $item->sreturn ?? 'N/A' }}<input type="hidden" name="sreturn[]"
                                                    value="{{ $item->sreturn }}"></td>
                                            <td>{{ $item->item->variation->name }}</td>
                                            <td>{{ $item->item->hsn_hac }}</td>
                                            <td>{{ number_format(floatval($item->rate ?? 0), 2) }}<input type="hidden"
                                                    name="rates[]"
                                                    value="{{ number_format(floatval($item->rate ?? 0), 2) }}"></td>
                                            <td>{{ $item->tax }}<input type="hidden"
                                                    name="taxes[]"
                                                    value="{{ number_format(floatval($item->tax ?? 0), 2) }}"></td>
                                            <td>{{ number_format($item->cess, 2, '.', '') ?? '0.00' }}</td>
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
                            <!-- amount_before_tax Tax -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="amount_before_tax" class="form-label text-end">Amount Before Tax</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="amount_before_tax" value="{{ number_format($salesBook->amount_before_tax, 2, '.', '') }}" name="amount_before_tax" min="0" readonly>
                                    @error('amount_before_tax')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Discount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="discount" class="form-label">Discount(-)</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="discount" name="discount" min="0" value="{{ number_format($salesBook->discount, 2, '.', '') }}" readonly>
                                    <div id="discount-error" class="text-danger"></div>
                                </div>
                            </div>
                            <!-- Discount Value -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="discount" class="form-label text-end">Discounted Amount(-)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="discount_value" name="discount_value"
                                        min="0" value="{{ number_format($salesBook->discount_value, 2, '.', '') }}" readonly>
                                    @error('discount_value')
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
                                    <input type="number" class="form-control" id="igst" value="{{ number_format($salesBook->igst, 2, '.', '') }}" name="igst" min="0" readonly>
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
                                    <input type="number" class="form-control" id="cgst" value="{{ number_format($salesBook->sgst, 2, '.', '') }}" name="cgst" min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control" id="sgst" value="{{ number_format($salesBook->cgst, 2, '.', '') }}" name="sgst" min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Cess Tax -->
                                <div class="row">
                                    <div class="col-md-3 mb-3"></div>
                                    <div class="col-md-3 mb-3">
                                        <label for="igst" class="form-label text-end">Cess</label>
                                    </div>
                                    <div class="col-md-2 mb-3"></div>
                                    <div class="col-md-4 mb-3">
                                        <input type="number" class="form-control" id="total_cess" value="{{ number_format($salesBook->cess, 2, '.', '') }}"
                                            name="total_cess" min="0" readonly>
                                        @error('igst')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            <!-- Other Expenses -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="other_expense" class="form-label">Other Expense(+)</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="other_expense" value="{{ number_format($salesBook->other_expense, 2, '.', '') }}" min="0" name="other_expense" readonly>
                                    <div id="other_expense-error" class="text-danger"></div>
                                </div>
                            </div>
                            <!-- Round Off -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="round_off" class="form-label">Round Off(-/+)</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="round_off" name="round_off" value="{{ number_format($salesBook->round_off, 2, '.', '') }}" step="any" readonly>
                                    <div id="round_off-error" class="text-danger"></div>
                                </div>
                            </div>
                            <!-- Total Invoice value -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="grand_total" class="form-label">Total Invoice value</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="grand_total" name="grand_total" value="{{ number_format($salesBook->grand_total, 2, '.', '') }}" readonly>
                                </div>
                            </div>
                            <!-- Given Amount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="given_amount" class="form-label text-end">Given Amount</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="given_amount" name="given_amount" value="{{ number_format($salesBook->recived_amount, 2, '.', '') }}" min="0" readonly>
                                    @error('given_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Remaining Balance -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="remaining_blance" class="form-label text-end">Remaining Balance </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="remaining_blance" name="remaining_blance" value="{{ number_format($salesBook->balance_amount, 2, '.', '') }}" min="0" readonly>
                                    @error('remaining_blance')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- UPI -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="remaining_blance" class="form-label text-end">Payment Type </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="payment_type" name="payment_type" value="{{ $salesBook->payment_type }}" readonly>
                                    @error('remaining_blance')
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
