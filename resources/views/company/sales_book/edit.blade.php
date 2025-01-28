@extends('company.layouts.app')

@section('style')
    <!-- Add any necessary styles here -->
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Edit Sales Invoice</span>
                </h5>
            </div>
        </div>
        <input type="hidden" name="companyState" id="companyState" value="{{ $companyState }}">
        <form role="form" action="{{ route('company.sales.book.update', $salesBook->id) }}" method="post" id="Sales_edit"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Edit Sales</h5>
                        <div class="card-body">
                            <!-- Purchase details form -->
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="date" id="date" name="date"
                                           value="{{  $salesBook->date }}">
                                </div>
                                <!-- dispatch Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dispatch" class="form-label">Dispatch</label>
                                    <input type="text" class="form-control" id="dispatch" name="dispatch"
                                        value="{{ $salesBook->dispatch_number }}">
                                </div>
                                <!-- customer Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="customer" class="form-label">Customer</label>
                                    <select class="form-select" id="customer" name="customer">
                                        <option selected>Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $salesBook->customer_id ? 'selected' : '' }} data-state="{{ $customer->state }}">
                                                {{ $customer->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- weight Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Place Of Supply</label>
                                    <input type="text" class="form-control" id="weight" name="weight"
                                        value="{{ $salesBook->item_weight }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Transport</label>
                                    <input type="text" class="form-control" id="transport" name="transport"
                                        value="{{ $salesBook->transport }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Vehicle No.</label>
                                    <input type="text" class="form-control" id="vehicle_no" name="vehicle_no"
                                        value="{{ $salesBook->vehicle_no }}">
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Item details form -->
                            <div class="row">
                                <!-- Item Selection -->
                                <div class="col-md-3 mb-3">
                                    <label for="item" class="form-label">Item</label>
                                    <select class="form-select" id="item">
                                        <option selected disabled>Select</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-tax="{{ $item->tax_rate }}"
                                                data-variation="{{ $item->variation_name }}" data-hsn="{{ $item->hsn_hac }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="item-error" class="text-danger"></div>
                                </div>
                                <!-- Quantity Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="qty" min="0">
                                    <div id="qty-error" class="text-danger"></div>
                                </div>
                                <!-- Amount per Unit Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="amount" class="form-label">Amount per Unit</label>
                                    <input type="number" class="form-control" id="amount" min="0">
                                    <div id="amount-error" class="text-danger"></div>
                                </div>
                                <!-- Add Item Button -->
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-info" id="addItem">Add Item</button>
                                </div>
                            </div>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Pre-fill items from the purchase book -->
                                    @foreach ($salesBook->salesbookitem as $index => $item)
                                    @php
                                        $formattedAmount = $item->amount;
                                        $amountWithoutCommas = str_replace(',', '', $formattedAmount); // Remove commas
                                        $integerAmount = intval($amountWithoutCommas);
                                    @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->item->name }}<input type="hidden" name="items[]"
                                                    value="{{ $item->item_id }}"></td>
                                            <td>{{ $item->quantity ?? 'N/A' }}<input type="hidden" name="quantities[]"
                                                    value="{{ $item->quantity }}"></td>
                                            <td>{{ $item->sreturn ?? 'N/A' }}<input type="hidden" name="sreturn[]"
                                                    value="{{ $item->sreturn }}"></td>
                                            <td>{{ $item->item->hsn_hac }}</td>
                                            <td>{{ $item->item->variation->name }}</td>
                                            <td>{{ number_format(floatval($item->rate ?? 0), 2) }}<input type="hidden"
                                                    name="rates[]"
                                                    value="{{ number_format(floatval($item->rate ?? 0), 2) }}"></td>
                                            <td>{{ $item->item->tax->rate }} %<input type="hidden" name="taxes[]"
                                                    value="{{ $item->tax }}"></td>
                                            <td>{{ number_format(floatval($item->amount ?? 0), 2) }}<input type="hidden"
                                                    name="totalAmounts[]"
                                                    value="{{ $item->amount }}"></td>
                                            <td><button type="button"
                                                    class="btn btn-danger btn-sm removeItem">Remove</button></td>
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
                                        value="{{ $salesBook->amount_before_tax }}"
                                        name="amount_before_tax">
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
                                        value="{{ $salesBook->igst }}" name="igst"
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
                                        value="{{ $salesBook->cgst }}" name="cgst"
                                        min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="text" class="form-control" id="sgst"
                                        value="{{ $salesBook->sgst }}" name="sgst"
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
                                        value="{{ $salesBook->other_expense }}" min="0"
                                        name="other_expense">
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
                                        min="0" value="{{ $salesBook->discount }}">
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
                                        value="{{ $salesBook->round_off }}" step="any">
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
                                        value="{{ $salesBook->grand_total }}" min="0"
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
                                        min="0">
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
        let amountBeforeTax = 0;
        let itemsTableBody = $("#itemsTable tbody");
        const companyStateValue = $('#companyState').val();

        // Vendor change event
        $("#customer").change(function() {
            const selectedVendorState = $('#customer option:selected').data('state');
            calculateTotals(selectedVendorState); // Recalculate totals when vendor changes
        });

        // Add Item Button Click
        $("#addItem").click(function() {
            let item = $("#item").val();
            let itemName = $("#item option:selected").text();
            let itemTax = parseFloat($("#item option:selected").data('tax')) || 0; // Handle missing tax rate
            let itemVariation = $("#item option:selected").data('variation');
            let hsn = $('#item option:selected').data('hsn');
            let qty = $("#qty").val() || 0; // Convert quantity to integer
            let amount = parseFloat($("#amount").val()) || 0; // Convert amount to float

            // Validate item, quantity, and amount before adding
            if (!item || qty <= 0 || amount <= 0) {
                alert('Please fill out all required fields before adding an item.');
                return;
            }

            let totalAmount = qty * amount; // Calculate total amount (quantity * unit price)
            let totalTax = Math.round((totalAmount * itemTax) / 100); // Calculate total tax as an integer

            // Add the item as a new row in the table
            let row = `<tr>
                <td>${itemsTableBody.children().length + 1}</td>
                <td>${itemName}<input type="hidden" name="items[]" value="${item}"></td>
                <td>${qty}<input type="hidden" name="quantities[]" value="${qty}"></td>
                <td>0<input type="hidden" name="sreturn[]" value="0"></td>
                <td>${hsn}</td>
                <td>${itemVariation}</td>
                <td>${amount.toFixed(2)}<input type="hidden" name="rates[]" value="${amount.toFixed(2)}"></td>
                <td>${itemTax}% <input type="hidden" name="taxes[]" value="${totalTax}"></td>
                <td>${Math.round(totalAmount)}<input type="hidden" name="totalAmounts[]" value="${Math.round(totalAmount)}"></td>
                <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
            </tr>`;

            itemsTableBody.append(row);

            // Recalculate totals after adding the item
            const selectedVendorState = $('#customer option:selected').data('state');
            calculateTotals(selectedVendorState); // Call function to update totals

            // Clear input fields after adding the item
            $("#item").val("");
            $("#qty").val("");
            $("#amount").val("");
        });

        // Remove Item Button Click with event delegation
        itemsTableBody.on('click', '.removeItem', function() {
            const row = $(this).closest('tr');
            row.remove();
            recalculateItemNumbers();

            const selectedVendorState = $('#customer option:selected').data('state');
            console.log(selectedVendorState);

            calculateTotals(selectedVendorState);
        });

        // Recalculate item numbers
        function recalculateItemNumbers() {
            itemsTableBody.children().each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Recalculate Totals based on vendor state
        function calculateTotals(selectedState) {

            let totalTax = 0;
            let grandTotal = 0;
            let totalBeforeTax = 0;
            var companyStateValue = $('#companyState').val();
            console.log(companyStateValue + '<=>' + selectedState);

            // Calculate grand total and amount before tax
            $("input[name='totalAmounts[]']").each(function() {
                let totalAmount = parseInt($(this).val()) || 0; // Convert totalAmount to integer
                totalBeforeTax += totalAmount;
                grandTotal += totalAmount;
            });

            // Calculate total tax
            $("input[name='taxes[]']").each(function() {
                let taxAmount = parseInt($(this).val()) || 0; // Convert taxAmount to integer
                totalTax += taxAmount;
            });

            // Parse other expense, discount, and round off values
            let otherExpense = parseInt($("#other_expense").val()) || 0; // Convert to integer
            let discount = parseInt($("#discount").val()) || 0; // Convert to integer
            let roundOff = parseInt($("#round_off").val()) || 0; // Convert to integer

            // Final total calculation
            let finalTotal = grandTotal + totalTax + otherExpense - discount + roundOff;

            // Display tax values based on the state
            if (companyStateValue === selectedState) {
                let halfTax = Math.round(totalTax / 2); // Split tax into two for CGST/SGST
                $('#cgst').val(halfTax); // Show formatted value for display
                $('#sgst').val(halfTax); // Show formatted value for display
                $('#igst').val('0');
            } else {
                $('#igst').val(totalTax); // Show full tax value for IGST
                $('#cgst').val('0');
                $('#sgst').val('0');
            }

            // Set the correct amount before tax and grand total
            $("#amount_before_tax").val(totalBeforeTax); // Show full amount
            $("#grand_total").val(finalTotal); // Show grand total
        }

        // Update totals on input changes
        $("#other_expense, #discount, #round_off").on('input', function() {
            const selectedVendorState = $('#customer option:selected').data('state');
            calculateTotals(selectedVendorState);
            updateRemainingBalance();
        });

        // Update remaining balance when the received amount changes
        $('#received_amount').on('input', function() {
            updateRemainingBalance();
        });

        // Handle form submission
        $("#Sales_edit").submit(function(e) {
            let itemsCount = $("input[name='items[]']").length;

            if (itemsCount === 0) {
                alert('Please add at least one item before submitting the form.');
                e.preventDefault();
            }
        });

        // Function to update remaining balance based on received amount and grand total
        function updateRemainingBalance() {
            const givenAmount = parseFloat($('#received_amount').val()) || 0;
            console.log(givenAmount);
            const calculatedTotalMain = parseFloat($('#grand_total').val()) || 0;
            const remainingBalance = calculatedTotalMain - givenAmount;
            $('#balance_amount').val(remainingBalance.toFixed(2));
        }
    });
</script>

@endsection
