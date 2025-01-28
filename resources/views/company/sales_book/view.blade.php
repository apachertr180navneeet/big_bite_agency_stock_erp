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
                                        value="{{ $salesBook->date }}">
                                </div>
                                <!-- dispatch Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dispatch" class="form-label">Dispatch</label>
                                    <input type="text" class="form-control" id="dispatch" name="dispatch"
                                        value="{{ $salesBook->dispatch_number }}">
                                </div>
                                <!-- customer Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="customer" class="form-label">Costomer</label>
                                    <select class="form-select" id="customer" name="customer">
                                        <option selected>Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $salesBook->customer_id ? 'selected' : '' }}>
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
                                        min="0" value="{{ number_format((float) $salesBook->discount, 2) }}">
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
                                        value="{{ number_format((float) $salesBook->round_off, 2) }}" step="any">
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
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('tr').each(function() {
                // Get the tax rate from the `data-tax` attribute
                const taxRate = parseFloat($(this).data('tax')) || 0;

                // Find the corresponding input field for taxes and set the taxRate value
                $(this).find('input[name="taxes[]"]').val(taxRate.toFixed(2));

                // Optionally, update the display if needed
                $(this).find('.tax-display').text(taxRate.toFixed(2) + '%');
            });



            let itemCount = {{ count($salesBook->salesbookitem) }};

            // Function to calculate total tax, grand total, and update fields
            function calculateTotal() {
                let totalTax = 0;
                let grandTotal = 0;

                // Calculate total tax and grand total from items
                $('#itemsTable tbody tr').each(function() {
                    const tax = parseFloat($(this).find('input[name="taxes[]"]').val()) || 0;
                    const totalAmount = parseFloat($(this).find('input[name="totalAmounts[]"]').val()) || 0;

                    totalTax += tax;
                    grandTotal += totalAmount;
                });

                // Incorporate other expenses, discount, and round off into the grand total
                const otherExpense = parseFloat($('#other_expense').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                const roundOff = parseFloat($('#round_off').val()) || 0;

                grandTotal = grandTotal + otherExpense - discount + roundOff;

                // Update the total tax and grand total fields
                $('#total_tax').val(totalTax.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));
            }

            // Handle adding new items
            $('#addItem').on('click', function() {
                const item = $('#item option:selected');
                const qty = parseFloat($('#qty').val());
                const amountPerUnit = parseFloat($('#amount').val());
                const taxRate = parseFloat(item.data('tax'));
                const variation = item.data('variation');

                if (item.val() && !isNaN(qty) && qty > 0 && !isNaN(amountPerUnit) && amountPerUnit > 0 && !
                    isNaN(taxRate)) {
                    // Calculate the tax and total amount
                    const totalAmount = qty * amountPerUnit;
                    const tax = totalAmount * (taxRate / 100);
                    const totalWithTax = totalAmount + tax;

                    // Increment the item count
                    itemCount++;

                    // Append the new row to the items table
                    $('#itemsTable tbody').append(`
                    <tr>
                        <td>${itemCount}</td>
                        <td>${item.text()}<input type="hidden" name="items[]" value="${item.val()}"></td>
                        <td>${qty}<input type="hidden" name="quantities[]" value="${qty}"></td>
                        <td>${variation}</td>
                        <td>${totalAmount.toFixed(2)}<input type="hidden" name="rates[]" value="${totalAmount.toFixed(2)}"></td>
                        <td>${tax.toFixed(2)}<input type="hidden" name="taxes[]" value="${tax.toFixed(2)}"></td>
                        <td>${totalWithTax.toFixed(2)}<input type="hidden" name="totalAmounts[]" value="${totalWithTax.toFixed(2)}"></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
                    </tr>
                `);

                    // Recalculate the total tax and grand total
                    calculateTotal();
                } else {
                    alert('Please fill in all fields with valid values before adding an item.');
                }
            });

            // Handle the removal of items
            $('#itemsTable').on('click', '.removeItem', function() {
                $(this).closest('tr').remove();
                itemCount--;
                calculateTotal();
            });

            // Recalculate totals when other expenses, discount, or round off changes
            $('#other_expense, #discount, #round_off').on('input', calculateTotal);
        });
    </script>
@endsection
