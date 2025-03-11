@extends('company.layouts.app')

@section('style')
    <!-- Add any necessary styles here -->
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Sales Invoice</span>
                </h5>
            </div>
        </div>
        <input type="hidden" name="companyState" id="companyState" value="{{ $companyState }}">
        <form role="form" action="{{ route('company.sales.book.store') }}" method="post" id="coustomer_add"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Add Sales</h5>
                        <div class="card-body">
                            <!-- Display validation errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- Purchase details form -->
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="text" id="date" name="date"
                                        value="{{ $currentDate }}" readonly>
                                </div>
                                <!-- Invoice Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dispatch" class="form-label">Dispatch</label>
                                    <input type="text" class="form-control" id="dispatch" name="dispatch"
                                        value="{{ $invoiceNumber }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sub_compnay_id" class="form-label">Sub Comapny</label>
                                    <select class="form-select" id="sub_compnay_id" name="sub_compnay_id" required>
                                        <option value="">Select</option>
                                        @foreach ($subComapnys as $subComapny)
                                            <option value="{{ $subComapny->id }}"
                                                {{ old('sub_compnay_id') == $subComapny->id ? 'selected' : '' }}>{{ $subComapny->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sub_compnay_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- customer Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="customer" class="form-label">Customer</label>
                                    <select class="form-select" id="customer" name="customer">
                                        <option value="">Select</option>
                                        
                                    </select>
                                    <input type="hidden" class="form-control" id="weight" name="weight"
                                        value="0">
                                    <input type="hidden" class="form-control" id="transport" name="transport"
                                        value="0">
                                    <input type="hidden" class="form-control" id="vehicle_no" name="vehicle_no"
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Item details form -->
                            <div class="row">
                                <!-- Item Selection -->
                                <div class="col-md-3 mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category">
                                        <option selected>Select</option>
                                    </select>
                                    <div id="item_error" class="text-danger"></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="item" class="form-label">Item</label>
                                    <select class="form-select" id="item">
                                        <option selected>Select</option>
                                    </select>
                                    <div id="item_error" class="text-danger"></div>
                                </div>
                                <!-- Quantity Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="qty" min="0">
                                </div>
                                <!-- Amount per Unit Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="amount" class="form-label">Amount per Unit</label>
                                    <input type="number" class="form-control" id="amount" min="0">
                                </div>
                                <!-- Add Item Button -->
                                <div class="col-md-3 mb-3">
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
                                        <th>HSN</th>
                                        <th>Category</th>
                                        <th>Rate</th>
                                        <th>Tax</th>
                                        <th>Cess</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamically added rows will appear here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="card-body">
                            <!-- amount_before_tax Tax -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="amount_before_tax" class="form-label text-end">Amount Before Tax</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="amount_before_tax" value="0" name="amount_before_tax" min="0" readonly />
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
                                    <input type="text" class="form-control" id="discount" name="discount" min="0" value="0" />
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
                                    <input type="number" class="form-control" id="discount_value" name="discount_value" min="0" value="0" readonly />
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
                                    <input type="number" class="form-control" id="igst" value="0" name="igst" min="0" readonly />
                                    @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- CGST/SGST Tax -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-3 mb-3">
                                    <label for="cgst" class="form-label text-end">CGST/SGST</label>
                                </div>
                                <div class="col-md-2 mb-3"></div>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control" id="cgst" value="0" name="cgst" min="0" readonly />
                                    @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control" id="sgst" value="0" name="sgst" min="0" readonly />
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
                                    <input type="number" class="form-control" id="total_cess" value="0" name="total_cess" min="0" readonly />
                                    @error('igst')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Other Expenses -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="other_expense" class="form-label text-end">Fate(+)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="other_expense" value="0" min="0" name="other_expense" />
                                    @error('other_expense')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Round Off -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="round_off" class="form-label text-end">Round Off(-/+)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="round_off" name="round_off" value="0" step="any" readonly />
                                    @error('round_off')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Total Invoice value -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="grand_total" class="form-label text-end">Total Invoice value </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="grand_total" name="grand_total" value="0" min="0" readonly />
                                    @error('grand_total')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Given Amount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="given_amount" class="form-label text-end">Given Amount</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="given_amount" name="given_amount" value="0" min="0" required />
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
                                    <input type="number" class="form-control" id="remaining_blance" name="remaining_blance" value="0" min="0" readonly />
                                    @error('remaining_blance')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                            <!-- Payment Type -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="payment_type" class="form-label text-end">Payment Type </label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" id="payment_type" name="payment_type" required>
                                        <option value="">Select</option>
                                        <option value="UPI">UPI</option>
                                        <option value="RTGS">RTGS</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                    @error('payment_type')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                            <!-- Save Button -->
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
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
            let itemCount = 0;
            let totalTax = 0;
            let totalCess = 0;
            let grandTotal = 0;
            let amountBeforeTax = 0;
        
            const $vendor = $('#customer');
            const $companyState = $('#companyState');
            const $cgst = $('#cgst');
            const $sgst = $('#sgst');
            const $igst = $('#igst');
            const $amountBeforeTax = $('#amount_before_tax');
            const $grandTotal = $('#grand_total');
            const $otherExpense = $('#other_expense');
            const $discount = $('#discount');
            const $roundOff = $('#round_off');
            const $givenAmount = $('#given_amount');
            const $remainingBalance = $('#remaining_blance');
            const $totalCess = $('#total_cess'); // New CESS field
            const $itemsTableBody = $('#itemsTable tbody');
        
            // **Calculate GST & Update Totals**
            function updateTax() {
                totalTax = 0;
                totalCess = 0;
        
                $itemsTableBody.find('tr').each(function () {
                    totalTax += parseFloat($(this).find('input[name="taxes[]"]').val()) || 0;
                    totalCess += parseFloat($(this).find('input[name="cess[]"]').val()) || 0;
                });
        
                $totalCess.val(totalCess.toFixed(2)); // Update total CESS field
        
                const selectedState = $vendor.find('option:selected').data('state');
                const companyStateValue = $companyState.val();
        
                if (companyStateValue == selectedState) {
                    let cgst = totalTax / 2;
                    $cgst.val(cgst.toFixed(2));
                    $sgst.val(cgst.toFixed(2));
                    $igst.val('0.00');
                } else {
                    $igst.val(totalTax.toFixed(2));
                    $cgst.val('0.00');
                    $sgst.val('0.00');
                }
            }
        
            // **Calculate and Update All Totals**
            function calculateTotal() {
                let amountBeforeTax = parseFloat($amountBeforeTax.val()) || 0;
                let discount = parseFloat($discount.val()) || 0;
                let otherExpense = parseFloat($otherExpense.val()) || 0;
                let igst = parseFloat($igst.val()) || 0;
                let cgst = parseFloat($cgst.val()) || 0;
                let sgst = parseFloat($sgst.val()) || 0;
        
                // **Calculate Discounted Amount**
                let discountValue = amountBeforeTax - discount;
                if (discountValue < 0) discountValue = 0; // Prevent negative values
                $("#discount_value").val(discountValue.toFixed(2));
        
                // **Calculate Total Before Tax After Discount**
                let totalBeforeTax = discountValue;
        
                // **Calculate Total Tax**
                let totalTax = igst + cgst + sgst;
        
                // **Calculate Grand Total Before Rounding**
                grandTotal = totalBeforeTax + totalTax + totalCess + otherExpense;
        
                // **Calculate Round Off**
                let decimalPart = grandTotal - Math.floor(grandTotal);
                let roundOff = 0;
        
                if (decimalPart >= 0.75) {
                    roundOff = 1 - decimalPart;  // Round Up
                } else if (decimalPart <= 0.50) {
                    roundOff = -decimalPart; // Round Down
                }
        
                let finalTotal = grandTotal + roundOff;
        
                // **Set Values in Input Fields**
                $roundOff.val(roundOff.toFixed(2));
                $grandTotal.val(finalTotal.toFixed(2));
            }
        
            // **Trigger Calculation on Input Changes**
            $("#amount_before_tax, #discount, #other_expense, #igst, #cgst, #sgst").on("input", function () {
                calculateTotal();
            });
        
            // **Update Remaining Balance**
            function updateRemainingBalance() {
                let givenAmount = parseFloat($givenAmount.val()) || 0;
                let finalTotal = parseFloat($grandTotal.val()) || 0;
                let remainingBalance = finalTotal - givenAmount;
                $remainingBalance.val(remainingBalance.toFixed(2));
            }
        
            // **Event Listener for Given Amount**
            $givenAmount.on("input", function () {
                updateRemainingBalance();
            });
        
            // **Add Item to Table**
            $('#addItem').on('click', function () {
                const category = $('#category option:selected').text();
                const categoryId = $('#category').val();
                const item = $('#item option:selected').text();
                const itemId = $('#item').val();
                const hsn = $('#item option:selected').data('hsn');
                const taxRate = $('#item option:selected').data('tax') || 0;
                const qty = parseFloat($('#qty').val());
                const amountPerUnit = parseFloat($('#amount').val());
        
                if (itemId && !isNaN(qty) && !isNaN(amountPerUnit)) {
                    const totalAmount = qty * amountPerUnit;
                    const tax = totalAmount * (taxRate / 100);
                    const totalWithTax = totalAmount + tax;
                    const cess = taxRate === 28 ? (totalAmount * 12) / 100 : 0; // CESS calculation
        
                    itemCount++;
                    amountBeforeTax += totalAmount;
                    totalTax += tax;
                    totalCess += cess; // Update total CESS
                    grandTotal += totalWithTax;
        
                    $amountBeforeTax.val(amountBeforeTax.toFixed(2));
                    $totalCess.val(totalCess.toFixed(2)); // Update total CESS field
        
                    // Append new item row
                    $itemsTableBody.append(`
                        <tr>
                            <td>${itemCount}</td>
                            <td>${item}<input type="hidden" name="items[]" value="${itemId}"></td>
                            <td>${qty}<input type="hidden" name="quantities[]" value="${qty}"></td>
                            <td>${hsn}</td>
                            <td>${category}<input type="hidden" name="categorys[]" value="${categoryId}"></td>
                            <td>${amountPerUnit.toFixed(2)}<input type="hidden" name="rates[]" value="${amountPerUnit.toFixed(2)}"></td>
                            <td>${tax.toFixed(2)}<input type="hidden" name="taxes[]" value="${tax.toFixed(2)}"></td>
                            <td>${cess.toFixed(2)}<input type="hidden" name="cess[]" value="${cess.toFixed(2)}"></td>
                            <td>${totalAmount.toFixed(2)}<input type="hidden" name="totalAmounts[]" value="${totalAmount.toFixed(2)}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
                        </tr>
                    `);
        
                    updateTax();
                    calculateTotal();
                }
            });
        
            // **Remove Item from Table**
            $(document).on('click', '.removeItem', function () {
                const row = $(this).closest('tr');
                const taxToRemove = parseFloat(row.find('input[name="taxes[]"]').val()) || 0;
                const cessToRemove = parseFloat(row.find('input[name="cess[]"]').val()) || 0;
                const amountToRemove = parseFloat(row.find('input[name="totalAmounts[]"]').val()) || 0;
        
                totalTax -= taxToRemove;
                totalCess -= cessToRemove;
                grandTotal -= amountToRemove + taxToRemove + cessToRemove;
                amountBeforeTax -= amountToRemove;
        
                row.remove();
                itemCount--;
        
                updateTax();
                calculateTotal();
            });
        
            // **Update GST When Vendor Changes**
            $vendor.on('change', function () {
                updateTax();
            });
        });

        // Get Vedor by sub company
        $(document).ready(function () {
            $('#sub_compnay_id').on('change', function () {
                let subCompanyId = $(this).val();
                let vendorDropdown = $('#customer');
                let categoryDropdown = $('#category');
        
                // Define URLs and replace the placeholder dynamically
                let vendorUrl = "{{ route('ajax.getCustomers', ['sub_compnay_id' => '__ID__']) }}".replace('__ID__', subCompanyId);
                let categoryUrl = @json(route('ajax.getCategories', ['sub_company_id' => '__ID__']));
        
                if (subCompanyId) {
                    // Fetch Vendors
                    $.ajax({
                        url: vendorUrl,
                        type: 'GET',
                        dataType: 'json',
                        success: function (datavendor) {
                            vendorDropdown.empty().append('<option value="">Select</option>');
                            $.each(datavendor, function (index, vendor) {
                                vendorDropdown.append(`<option value="${vendor.id}" data-state="${vendor.state}">${vendor.full_name}</option>`);
                            });
                        }
                    });
        
                    // Fetch Categories
                    $.ajax({
                        url: categoryUrl.replace('__ID__', subCompanyId),
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            categoryDropdown.empty().append('<option value="">Select</option>');
                            $.each(data, function (index, category) {
                                categoryDropdown.append(`<option value="${category.id}">${category.name}</option>`);
                            });
                        }
                    });
                } else {
                    // Reset dropdowns if no sub-company is selected
                    vendorDropdown.empty().append('<option value="">Select</option>');
                    categoryDropdown.empty().append('<option value="">Select</option>');
                }
            });
        });
        
        // Get Item by category
        $(document).ready(function () {
            $('#category').on('change', function () {
                let categoryId = $(this).val();
                let itemDropdown = $('#item');
        
                // Define the route with a placeholder and replace it dynamically
                let itemUrl = @json(route('ajax.getItems', ['category_id' => '__ID__']));
        
                if (categoryId) {
                    $.ajax({
                        url: itemUrl.replace('__ID__', categoryId),
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            itemDropdown.empty().append('<option selected>Select</option>');
                            $.each(data, function (index, item) {
                                itemDropdown.append(`
                                            <option 
                                                value="${item.id}" 
                                                data-tax="${item.tax.rate}" 
                                                data-variation="${item.variation.name}" 
                                                data-hsn="${item.hsn_hac}">
                                                ${item.name}
                                            </option>
                                        `);
                            });
                        }
                    });
                } else {
                    // Reset item dropdown if no category is selected
                    itemDropdown.empty().append('<option selected>Select</option>');
                }
            });
        });
    </script>
@endsection
