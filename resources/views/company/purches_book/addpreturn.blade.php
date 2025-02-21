@extends('company.layouts.app')

@section('style')
    <!-- Add any necessary styles here -->
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Return Purchase</span>
                </h5>
            </div>
        </div>
        <form role="form" action="{{ route('company.purches.book.preturn.save') }}" method="post"
            id="purchase_edit" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Return Purchase</h5>
                        <div class="card-body">
                            <!-- Purchase details form -->
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="date" id="date" name="date"
                                        value="" required>
                                    <div id="date-error" class="text-danger"></div>
                                </div>
                                <!-- Invoice Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="vendor" class="form-label">Invoice</label>
                                    <select class="form-select" id="invoice" name="invoice" required>
                                        <option selected disabled>Select</option>
                                        @foreach ($purchesBooks as $purchesBook)
                                            <option value="{{ $purchesBook->id }}"
                                                data-state="{{ $purchesBook->invoice_number }}">{{ $purchesBook->invoice_number }}</option>
                                        @endforeach
                                    </select>
                                    <div id="vendor-error" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="puraches_book_id" id="puraches_book_id" value="">
                        <div class="card-body">
                            <!-- Items Table -->
                            <table class="table table-bordered mt-4" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Item</th>
                                        <th>current Quantity</th>
                                        <th>Return Quantity</th>
                                        <th>Variation</th>
                                        <th>Rate</th>
                                        <th>Tax</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        value=""
                                        name="amount_before_tax" min="0">
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
                                    <input type="number" class="form-control" id="igst"
                                        value="" name="igst"
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
                                        value="" name="cgst"
                                        min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control" id="sgst"
                                        value="" name="sgst"
                                        min="0" readonly>
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
                                        value=""
                                        min="0" name="other_expense">
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
                                    <input type="number" class="form-control" id="discount" name="discount"
                                        min="0" value="">
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
                                    <input type="number" class="form-control" id="round_off" name="round_off"
                                        value="" step="any">
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
                                        value="" readonly>
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
            // When invoice is selected
            $('#invoice').on('change', function() {
                let invoiceId = $(this).val();
                if (!invoiceId) return;
        
                // Set the selected purchase_book_id in hidden input
                $('#puraches_book_id').val(invoiceId);
        
                // Fetch purchase details
                $.ajax({
                    url: '{{ route("ajax.getPurchaseDetails", "") }}/' + invoiceId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            console.log("Setting values..."); // Debugging message
        
                            // Ensure values are not null or undefined before setting
                            $('#amount_before_tax').val(parseFloat(response.purchase.amount_before_tax) || 0);
                            $('#igst').val(parseFloat(response.purchase.igst) || 0);
                            $('#cgst').val(parseFloat(response.purchase.cgst) || 0);
                            $('#sgst').val(parseFloat(response.purchase.sgst) || 0);
                            $('#other_expense').val(parseFloat(response.purchase.other_expense) || 0);
                            $('#discount').val(parseFloat(response.purchase.discount) || 0);
                            $('#round_off').val(parseFloat(response.purchase.round_off) || 0);
                            $('#grand_total').val(parseFloat(response.purchase.grand_total) || 0);
        
                            // Populate product details in itemsTable
                            let itemsHtml = "";
                            response.items.forEach((item, index) => {
                                itemsHtml += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.item_name}<input type="hidden" name="items[]" value="${item.item_id}"></td>
                                        <td>${item.current_quantity}</td>
                                        <td><input type="number" class="form-control returnQty" name="return_quantities[]" min="1" max="${item.current_quantity}" value="${item.preturn}"></td>
                                        <td>${item.variation}</td>
                                        <td>${parseFloat(item.rate).toFixed(2)}<input type="hidden" name="rates[]" value="${item.rate}"></td>
                                        <td><span class="">${item.tax}</span><input type="hidden" name="taxes[]" value="${item.tax}"></td>
                                        <td><span class="">${item.amount}</span><input type="hidden" name="totalAmounts[]" value="${item.amount}"></td>
                                    </tr>`;
                            });
        
                            $('#itemsTable tbody').html(itemsHtml);
                            //updateOverallTotals(); // Call function to recalculate totals
                        } else {
                            alert("Failed to fetch purchase details.");
                        }
                    },
                    error: function() {
                        alert("Error fetching purchase details.");
                    }
                });
            });
        
            function updateOverallTotals() {
                let totalBeforeTax = 0;
                let totalTax = 0;
                let totalAmount = 0;
                let otherExpenses = parseFloat($('#other_expense').val()) || 0;
                let discount = parseFloat($('#discount').val()) || 0;
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
                    let taxAmount = (amountBeforeTax * taxPercent) / 100;
        
                    $(this).find('input[name="taxes[]"]').val(taxAmount.toFixed(2));
                    $(this).find('.taxAmountDisplay').text(taxAmount.toFixed(2));
        
                    let totalRowAmount = amountBeforeTax + taxAmount;
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
        
                let totalInvoiceValue = totalAmount + otherExpenses - discount + roundOff + igst + cgst + sgst;
        
                $('#amount_before_tax').val(totalBeforeTax.toFixed(2));
                $('#grand_total').val(totalInvoiceValue.toFixed(2));
                let remainingBalance = totalInvoiceValue - givenAmount;
                $('#remaining_blance').val(remainingBalance.toFixed(2));
            }
        
            $(document).on('keyup change', '.itemQty, #vendor, #other_expense, #discount, #round_off, #given_amount', function() {
                updateOverallTotals();
            });
        
            updateOverallTotals();
        
        });
        
        
    </script>
@endsection
