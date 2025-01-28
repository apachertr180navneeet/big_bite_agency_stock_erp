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
                                <input class="form-control" type="date" id="date" name="date" value="{{ $salesBook->date }}">
                            </div>
                            <!-- dispatch Field -->
                            <div class="col-md-6 mb-3">
                                <label for="dispatch" class="form-label">Dispatch</label>
                                <input type="text" class="form-control" id="dispatch" name="dispatch" value="{{ $salesBook->dispatch_number }}">
                            </div>
                            <!-- customer Field -->
                            <div class="col-md-6 mb-3">
                                <label for="customer" class="form-label">Costomer</label>
                                <select class="form-select" id="customer" name="customer">
                                    <option selected>Select</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id == $salesBook->customer_id ? 'selected' : '' }}>{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- weight Field -->
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Delivery Location</label>
                                <input type="text" class="form-control" id="weight" name="weight" value="{{ $salesBook->item_weight }}">
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
                                        <td><input type="text" class="form-control itemQty" name="quantities[]"  value="{{ $item->quantity - $item->sreturn }}" max="{{ $item->quantity }}" min="1"></td>
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
                        <div class="row">
                            <div class="col-md-3 mb-3"></div>
                            <div class="col-md-3 mb-3">
                                <label for="amount_before_tax" class="form-label text-end">Amount Before Tax</label>
                            </div>
                            <div class="col-md-2 mb-3"></div>
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="amount_before_tax" value="{{ number_format((float)$salesBook->amount_before_tax, 2) }}" name="amount_before_tax" min="0" readonly>
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
                                <input type="text" class="form-control" id="igst" value="{{ number_format((float)$salesBook->igst, 2) }}" name="igst" min="0" readonly>
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
                                <input type="text" class="form-control" id="cgst" value="{{ number_format((float)$salesBook->cgst, 2) }}" name="cgst" min="0" readonly>
                                @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <input type="text" class="form-control" id="sgst" value="{{ number_format((float)$salesBook->sgst, 2) }}" name="sgst" min="0" readonly>
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
                                <input type="text" class="form-control" id="other_expense" value="{{ number_format((float)$salesBook->other_expense, 2) }}" min="0" name="other_expense">
                            </div>
                        </div>
                        <!-- Discount -->
                        <div class="row">
                            <div class="col-md-3 mb-3"></div>
                            <div class="col-md-5 mb-3">
                                <label for="discount" class="form-label text-end">Discount(-)</label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="discount" name="discount" min="0" value="{{ number_format( (float)$salesBook->discount, 2) }}">
                            </div>
                        </div>
                        <!-- Round Off -->
                        <div class="row">
                            <div class="col-md-3 mb-3"></div>
                            <div class="col-md-5 mb-3">
                                <label for="round_off" class="form-label text-end">Round Off(-/+)</label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="round_off" name="round_off" value="{{ number_format( (float)$salesBook->round_off, 2) }}" step="any">
                            </div>
                        </div>
                        <!-- Grand Total -->
                        <div class="row">
                            <div class="col-md-3 mb-3"></div>
                            <div class="col-md-5 mb-3">
                                <label for="grand_total" class="form-label text-end">Grand Total</label>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control" id="grand_total" name="grand_total" value="{{ number_format( (float)$salesBook->grand_total, 2) }}" min="0" readonly>
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
    $(document).ready(function () {
        // When the quantity is changed
        $('#itemsTable').on('input', '.itemQty', function () {
            var $row = $(this).closest('tr'); // Get the row of the quantity input

            // Parse the quantity and rate as floats
            var quantity = $row.find('input[name="quantities[]"]').val() || 0; // Ensure this is a number
            var rate = $row.find('input[name="rates[]"]').val() || 0; // Ensure this is a number
            var taxPercent = $row.find('input[name="taxespercent[]"]').val() || 0; // Get the tax percentage

            // Calculate total amount before tax
            var totalBeforeTax = quantity * rate;
            console.log(rate,quantity,totalBeforeTax);

            // Calculate tax
            var taxAmount = (totalBeforeTax * taxPercent) / 100;

            // Calculate total amount including tax
            var totalAmount = totalBeforeTax + taxAmount;

            // Update the tax and total amount fields
            $row.find('.taxAmountDisplay').text(taxAmount.toFixed(2)); // Update the displayed tax
            $row.find('input[name="taxes[]"]').val(taxAmount.toFixed(2)); // Update the hidden tax input
            $row.find('.totalAmountDisplay').text(totalBeforeTax.toFixed(2)); // Update the displayed total amount
            $row.find('input[name="totalAmounts[]"]').val(totalBeforeTax.toFixed(2)); // Update the hidden total amount input

            // Optionally, update the amount before tax, total tax, and grand total at the bottom
            updateTotals();
        });

        // Function to update the overall totals
        function updateTotals() {
            var totalBeforeTax = 0;
            var totalTax = 0;
            var grandTotal = 0;

            var igst = document.getElementById("igst").value;
            var cgst = document.getElementById("cgst").value;
            var sgst = document.getElementById("sgst").value;

            // Loop through each row to sum up the totals
            $('#itemsTable tbody tr').each(function () {
                var quantity = parseFloat($(this).find('.itemQty').val()) || 0;
                var rate = parseFloat($(this).find('input[name="rates[]"]').val()) || 0;
                var taxAmount = parseFloat($(this).find('input[name="taxes[]"]').val()) || 0;
                var totalAmount = parseFloat($(this).find('input[name="totalAmounts[]"]').val()) || 0;

                // Calculate totals
                totalBeforeTax += quantity * rate;
                totalTax += taxAmount;
                grandTotal += totalAmount + totalTax;
            });

            if(igst != 0){
                $('#igst').val(totalTax.toFixed(2));
            }else{
                var dividetax = totalTax /2;
                $('#cgst').val(dividetax.toFixed(2));
                $('#sgst').val(dividetax.toFixed(2));
            }

            // Update the summary fields
            $('#amount_before_tax').val(totalBeforeTax.toFixed(2));
            //$('#igst').val(totalTax.toFixed(2)); // Assuming you're using IGST for simplicity
            $('#grand_total').val(grandTotal.toFixed(2));
        }
    });
</script>
@endsection
