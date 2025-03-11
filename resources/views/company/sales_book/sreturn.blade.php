@extends('company.layouts.app')

@section('style')
<!-- Add any necessary styles here -->
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Sale Return</span>
            </h5>
        </div>
    </div>
    <form role="form" action="{{ route('company.sales.book.spreturn.save', $salesBook->id) }}" method="post" id="Sales_edit" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card mb-4">
                    <h5 class="card-header">Sales Return</h5>
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
                                <input type="text" class="form-control" id="dispatch" name="dispatch" value="{{ $salesBook->dispatch_number }}" readonly>
                            </div>
                            <!-- customer Field -->
                            <div class="col-md-6 mb-3">
                                <label for="customer" class="form-label">Costomer</label>
                                <select class="form-select" id="customer" name="customer" disabled>
                                    <option selected>Select</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id == $salesBook->customer_id ? 'selected' : '' }}>{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- weight Field -->
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Delivery Location</label>
                                <input type="text" class="form-control" id="weight" name="weight" value="{{ $salesBook->item_weight }}" readonly>
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
                                    <th>Variation</th>
                                    <th>Rate</th>
                                    <th>Tax</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Pre-fill items from the purchase book -->
                                @foreach ($salesBook->salesbookitem as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->item->name }}<input type="hidden" name="items[]" value="{{ $item->item_id }}"></td>
                                        <td>
                                            <input type="number" class="form-control itemQty" name="quantities[]"  
                                                value="{{ $item->sreturn }}" 
                                                max="{{ $item->quantity - $item->sreturn }}"
                                                oninput="validateQuantity(this)" 
                                                onchange="validateQuantity(this)">
                                            <small class="error-msg text-danger" style="display: none;"></small>
                                        </td>
                                        <td>{{ $item->item->variation->name }}</td>
                                        <td>{{ number_format(floatval($item->rate ?? 0), 2) }}<input type="hidden" name="taxespercent[]" value="{{ $item->item->tax->rate }}"><input type="hidden" name="rates[]" value="{{ $item->rate }}"></td>
                                        <td><span class="taxAmountDisplay">{{ number_format(floatval($item->tax ?? 0), 2) }}</span><input type="hidden" name="taxes[]" value="{{ number_format(floatval($item->tax ?? 0), 2) }}"></td>
                                        <td><span class="totalAmountDisplay">{{ number_format(floatval($item->amount ?? 0), 2) }}</span><input type="hidden" name="totalAmounts[]" value="{{ $item->amount }}"></td>
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
                                <input type="number" class="form-control" id="amount_before_tax"
                                    value="{{ number_format($salesBook->amount_before_tax, 2, '.', '') }}"
                                    name="amount_before_tax" min="0" readonly>
                                @error('amount_before_tax')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
                                    min="0" value="{{ number_format($salesBook->discount, 2, '.', '') }}" readonly>
                                @error('discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
                                <input type="number" class="form-control" id="igst"
                                    value="{{ number_format($salesBook->igst, 2, '.', '') }}" name="igst"
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
                                <input type="number" class="form-control" id="cgst"
                                    value="{{ number_format($salesBook->sgst, 2, '.', '') }}" name="cgst"
                                    min="0" readonly>
                                @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <input type="number" class="form-control" id="sgst"
                                    value="{{ number_format($salesBook->cgst, 2, '.', '') }}" name="sgst"
                                    min="0" readonly>
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
                                <input type="number" class="form-control" id="other_expense"
                                    value="{{ number_format($salesBook->other_expense, 2, '.', '') }}"
                                    min="0" name="other_expense" readonly>
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
                                <input type="number" class="form-control" id="round_off" name="round_off"
                                    value="{{ number_format($salesBook->round_off, 2, '.', '') }}" step="any" readonly>
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
                                <input type="text" class="form-control" id="grand_total" name="grand_total"
                                    value="{{ number_format($salesBook->grand_total, 2, '.', '') }}" readonly>
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
                    </div>
                    <!-- Save button -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
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
<script>
    $(document).ready(function() {
        function updateOverallTotals() {
            let totalBeforeTax = 0;
            let totalTax = 0;
            let totalAmount = 0;
            let otherExpenses = parseFloat($('#other_expense').val()) || 0;
            let amountBeforeTax = parseFloat($('#amount_before_tax').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;
            let discount_value = parseFloat($('#discount_value').val()) || 0;
            let roundOff = parseFloat($('#round_off').val()) || 0;
            let givenAmount = parseFloat($('#given_amount').val()) || 0;
    
            let companyState = $('#companyState').val();
            let vendorState = $('#vendor option:selected').data('state');
            let isIGSTApplicable = companyState !== vendorState; // Apply IGST if states are different
    
            $('#itemsTable tbody tr').each(function() {
                let quantity = parseFloat($(this).find('.itemQty').val()) || 0;
                let rate = parseFloat($(this).find('input[name="rates[]"]').val()) || 0;
                let taxPercent = parseFloat($(this).find('input[name="taxespercent[]"]').val()) || 0;
    
                let amountBeforeTax = quantity * rate;
                //alert(amountBeforeTax + ' tax');
                let taxAmount = (amountBeforeTax * taxPercent) / 100;
    
                $(this).find('input[name="taxes[]"]').val(taxAmount.toFixed(2));
                $(this).find('.taxAmountDisplay').text(taxAmount.toFixed(2));
    
                //let totalRowAmount = amountBeforeTax + taxAmount;
                let totalRowAmount = amountBeforeTax;
                $(this).find('input[name="totalAmounts[]"]').val(totalRowAmount.toFixed(2));
                $(this).find('.totalAmountDisplay').text(totalRowAmount.toFixed(2));
    
                totalBeforeTax += amountBeforeTax;
                totalTax += taxAmount;
                totalAmount += totalRowAmount;
            });
    
            if (isIGSTApplicable) {
                $('#igst').val(totalTax.toFixed(2));  
                $('#cgst').val('0.00'); 
                $('#sgst').val('0.00'); 
            } else {
                let halfTax = totalTax / 2;
                $('#igst').val('0.00'); 
                $('#cgst').val(halfTax.toFixed(2)); 
                $('#sgst').val(halfTax.toFixed(2)); 
            }
    
            let cgst = parseFloat($("#cgst").val()) || 0;
            let sgst = parseFloat($("#sgst").val()) || 0;
            let igst = parseFloat($("#igst").val()) || 0;

            let newdiscountamount  = totalAmount - discount;
            
            // Set value in an input field
            $('#discount_value').val(newdiscountamount);


            let totalInvoiceValue = newdiscountamount + otherExpenses + roundOff + igst + cgst + sgst;
    
            $('#amount_before_tax').val(totalBeforeTax.toFixed(2));
            $('#grand_total').val(totalInvoiceValue.toFixed(2));
            let remainingBalance = totalInvoiceValue - givenAmount;
            $('#remaining_blance').val(remainingBalance.toFixed(2));
        }
    
        $(document).on('keyup change', '.itemQty, #vendor, #other_expense, #discount, #round_off, #given_amount', function() {
            updateOverallTotals();
        });
    
        //updateOverallTotals();
    });
</script>
<script>
    function validateQuantity(input) {
        let max = parseInt(input.max);
        let min = parseInt(input.min);
        let value = input.value ? parseInt(input.value) : min;
        let errorMsg = input.nextElementSibling; // Target the <small> tag next to input
    
        if (value > max) {
            input.value = max; // Reset to max value
            errorMsg.innerText = "Value should not be more than " + max;
            errorMsg.style.display = "block"; // Show error message
        } 
        else if (value < min) {
            input.value = min; // Reset to min value
            errorMsg.innerText = "Value should not be less than " + min;
            errorMsg.style.display = "block"; // Show error message
        } 
        else {
            errorMsg.style.display = "none"; // Hide error message when valid
        }
    }
</script>
@endsection
