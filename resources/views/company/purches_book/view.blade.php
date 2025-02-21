@extends('company.layouts.app')

@section('style')
<!-- Add any necessary styles here -->
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">View Purchase Book</span>
            </h5>
        </div>
    </div>
    <input type="hidden" name="companyState" id="companyState" value="{{ $companyState }}">
    <form role="form" action="{{ route('company.purches.book.update', $purchaseBook->id) }}" method="post" id="purchase_edit" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">View Purchase</h5>
                    <div class="card-body">
                        <!-- Purchase details form -->
                        <div class="row">
                            <!-- Date Field -->
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input class="form-control" type="text" id="date" name="date" value="{{ \Carbon\Carbon::parse($purchaseBook->date)->format('d-m-Y') }}" readonly>
                                <div id="date-error" class="text-danger"></div>
                            </div>
                            <!-- Invoice Field -->
                            <div class="col-md-6 mb-3">
                                <label for="invoice" class="form-label">Invoice</label>
                                <input type="text" class="form-control" id="invoice" name="invoice" value="{{ $purchaseBook->invoice_number }}" readonly>
                                <div id="invoice-error" class="text-danger"></div>
                            </div>
                            <!-- Transport Field -->
                            <div class="col-md-6 mb-3">
                                <label for="transport" class="form-label">Sub Company</label>
                                <input type="text" class="form-control" id="sub_company" name="sub_company" value="{{ $subCompany->name }}" readonly>
                                <div id="transport-error" class="text-danger"></div>
                            </div>
                            <!-- Vendor Field -->
                            <div class="col-md-6 mb-3">
                                <label for="vendor" class="form-label">Vendor</label>
                                <select class="form-select" id="vendor" name="vendor" required disabled>
                                    <option selected disabled>Select</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ $vendor->id == $purchaseBook->vendor_id ? 'selected' : '' }} data-state="{{ $vendor->state }}">{{ $vendor->full_name }}</option>
                                    @endforeach
                                </select>
                                <div id="vendor-error" class="text-danger"></div>
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
                                    <th>HSN</th>
                                    <th>Variation</th>
                                    <th>Rate</th>
                                    <th>Tax</th>
                                    <th>Total Amount</th>
                                    {{--  <th>Action</th>  --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseBook->purchesbookitem as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->item->name }}<input type="hidden" name="items[]" value="{{ $item->item_id }}"></td>
                                        <td>{{ $item->quantity ?? 'N/A' }}<input type="hidden" name="quantities[]" value="{{ $item->quantity }}"></td>
                                        <td>{{ $item->preturn ?? 'N/A' }}<input type="hidden" name="preturn[]" value="{{ $item->preturn }}"></td>
                                        <td>{{ $item->item->hsn_hac }}</td>
                                        <td>{{ $item->item->variation->name }}</td>
                                        <td>{{ number_format($item->rate, 2, '.', '') ?? '0.00' }}<input type="hidden" name="rates[]" value="{{ number_format($item->rate, 2, '.', '') }}"></td>
                                        <td>{{ number_format($item->tax, 2, '.', '') ?? '0.00' }}<input type="hidden" name="taxes[]" value="{{ number_format($item->tax, 2, '.', '') }}"></td>
                                        <td>{{ number_format($item->amount, 2, '.', '') ?? '0.00' }}<input type="hidden" name="totalAmounts[]" value="{{ number_format($item->amount, 2, '.', '') }}"></td>
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
                                <input type="number" class="form-control" id="amount_before_tax" value="{{ number_format($purchaseBook->amount_before_tax, 2, '.', '') }}" name="amount_before_tax" min="0" readonly>
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
                                <input type="number" class="form-control" id="igst" value="{{ number_format($purchaseBook->igst, 2, '.', '') }}" name="igst" min="0" readonly>
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
                                <input type="number" class="form-control" id="cgst" value="{{ number_format($purchaseBook->sgst, 2, '.', '') }}" name="cgst" min="0" readonly>
                                @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <input type="number" class="form-control" id="sgst" value="{{ number_format($purchaseBook->cgst, 2, '.', '') }}" name="sgst" min="0" readonly>
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
                                <input type="number" class="form-control" id="other_expense" value="{{ number_format($purchaseBook->other_expense, 2, '.', '') }}" min="0" name="other_expense" readonly>
                                <div id="other_expense-error" class="text-danger"></div>
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
                                <input type="number" class="form-control" id="discount" name="discount" min="0" value="{{ number_format($purchaseBook->discount, 2, '.', '') }}" readonly>
                                <div id="discount-error" class="text-danger"></div>
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
                                <input type="number" class="form-control" id="round_off" name="round_off" value="{{ number_format($purchaseBook->round_off, 2, '.', '') }}" step="any" readonly>
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
                                <input type="text" class="form-control" id="grand_total" name="grand_total" value="{{ number_format($purchaseBook->grand_total, 2, '.', '') }}" readonly>
                            </div>
                        </div>
                        <!-- Given Amount -->
                        <div class="row">
                            <div class="col-md-3 mb-3"></div>
                            <div class="col-md-5 mb-3">
                                <label for="given_amount" class="form-label text-end">Given Amount</label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="number" class="form-control" id="given_amount" name="given_amount" value="{{ number_format($purchaseBook->given_amount, 2, '.', '') }}" min="0" readonly>
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
                                <input type="number" class="form-control" id="remaining_blance" name="remaining_blance" value="{{ number_format($purchaseBook->remaining_blance, 2, '.', '') }}" min="0" readonly>
                                @error('remaining_blance')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Save button -->
                    {{--  <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>  --}}
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')

@endsection
