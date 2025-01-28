@extends('company.layouts.app')

@section('style')
    <!-- Add any necessary styles here -->
    <!-- Add these inside your head tag -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Purchase Book</span>
                </h5>
            </div>
        </div>
        <input type="hidden" name="companyState" id="companyState" value="{{ $companyState }}">
        <form role="form" action="{{ route('company.purches.book.store') }}" method="post" id="coustomer_add"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Add Purchase</h5>
                        <div class="card-body">
                            <!-- Purchase details form -->
                            <div class="row">
                                <!-- Date Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="date" id="date" name="date"
                                        value="{{ old('date', $currentDate) }}" readonly>
                                    @error('date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Invoice Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="invoice" class="form-label">Invoice</label>
                                    <input type="text" class="form-control" id="invoice" name="invoice"
                                        value="{{ old('invoice', $invoiceNumber) }}" readonly>
                                    @error('invoice')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Vendor Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="vendor" class="form-label">Vendor</label>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                        + Add Vendor
                                    </button>
                                    <select class="form-select" id="vendor" name="vendor">
                                        <option selected>Select</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                {{ old('vendor') == $vendor->id ? 'selected' : '' }}
                                                data-state="{{ $vendor->state }}">{{ $vendor->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Transport Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="transport" class="form-label">Transport</label>
                                    <input type="text" class="form-control" id="transport" name="transport"
                                        value="{{ old('transport') }}">
                                    @error('transport')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Item details form -->
                        <div class="card-body">
                            <div class="row">
                                <!-- Item Selection -->
                                <div class="col-md-3 mb-3">
                                    <label for="item" class="form-label">Item</label>
                                    <select class="form-select" id="item">
                                        <option selected>Select</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-tax="{{ $item->tax->rate }}"
                                                data-variation="{{ $item->variation->name }}" data-hsn="{{ $item->hsn_hac }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="item_error" class="text-danger"></div>
                                </div>
                                <!-- Quantity Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="qty" min="0">
                                    <div id="qty_error" class="text-danger"></div>
                                </div>
                                <!-- Amount per Unit Field -->
                                <div class="col-md-3 mb-3">
                                    <label for="amount" class="form-label">Amount per Unit</label>
                                    <input type="number" class="form-control" id="amount" min="0">
                                    <div id="amount_error" class="text-danger"></div>
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
                                        <th>Variation</th>
                                        <th>Rate</th>
                                        <th>Tax</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamically added rows will appear here -->
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
                                    <input type="number" class="form-control" id="amount_before_tax" value="0"
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
                                    <input type="number" class="form-control" id="igst" value="0"
                                        name="igst" min="0" readonly>
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
                                    <input type="number" class="form-control" id="cgst" value="0"
                                        name="cgst" min="0" readonly>
                                    @error('igst')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control" id="sgst" value="0"
                                        name="sgst" min="0" readonly>
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
                                    <input type="number" class="form-control" id="other_expense" value="0"
                                        min="0" name="other_expense">
                                    @error('other_expense')
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
                                    <input type="number" class="form-control" id="discount" name="discount"
                                        min="0" value="0">
                                    @error('discount')
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
                                    <input type="text" class="form-control" id="round_off" name="round_off"
                                        value="0" step="any">
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
                                    <input type="number" class="form-control" id="grand_total" name="grand_total"
                                        value="0" min="0" readonly>
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
                                    <input type="number" class="form-control" id="given_amount" name="given_amount"
                                        value="0" min="0">
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
                                    <input type="number" class="form-control" id="remaining_blance"
                                        name="remaining_blance" value="0" min="0" readonly>
                                    @error('remaining_blance')
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
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Vendor Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter Name" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" placeholder="xxxx@xxx.xx" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" class="form-control" placeholder="" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address"name="address" rows="3"></textarea>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state">
                                <option selected>Select  State</option>
                                @foreach ($states as $state)
                                    <option value="{{$state->state_name}}" data-id="{{$state->state_id}}">{{$state->state_name}}</option>
                                @endforeach
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city">
                                <option selected>Select  City</option>
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="zipcode" class="form-label">Pincode</label>
                            <select class="form-select" id="zipcode">
                                <option selected>Select  Pincode</option>
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="gst" class="form-label">GST No.</label>
                            <input type="text" id="gst" class="form-control" placeholder="" />
                            <small class="error-text text-danger"></small>
                            <input type="hidden" id="role" value="vendor">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="AddItemven">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Add this before the closing body tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            //for select search
            $('#item').select2({
                placeholder: 'Select an item', // Optional placeholder text
                allowClear: true // Allow clearing the selection
            });


            let itemCount = 0;
            let totalTax = 0; // Track the total tax
            let grandTotal = 0;
            let amountBeforeTax = 0;

            // Vendor change handler
            $('#vendor').on('change', function() {
                // Get the selected vendor state and company state
                const selectedState = $('#vendor option:selected').data('state');
                const companyStateValue = $('#companyState').val();

                console.log(selectedState + ' =' + companyStateValue);

                // Reset tax values
                $('#cgst').val('0');
                $('#sgst').val('0');
                $('#igst').val('0');

                // Update IGST/CGST/SGST based on the states comparison
                if (companyStateValue == selectedState) {
                    // CGST and SGST will apply
                    const cgst = totalTax / 2;
                    $('#cgst').val(cgst.toFixed(2));
                    $('#sgst').val(cgst.toFixed(2));
                } else {
                    // IGST will apply
                    $('#igst').val(totalTax.toFixed(2));
                }
            });

            // Function to update the grand total field
            function updateGrandTotal() {
                const otherExpense = parseFloat($('#other_expense').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                const roundOff = parseFloat($('#round_off').val()) || 0;
                const calculatedTotal = grandTotal + otherExpense - discount + roundOff;
                $('#grand_total').val(calculatedTotal.toFixed(2));
            }

            // Function to update remaining balance
            function updateRemainingBalance() {
                const givenAmount = parseFloat($('#given_amount').val()) || 0;
                const calculatedTotalMain = $('#grand_total').val();
                const remainingBalance = calculatedTotalMain - givenAmount;
                $('#remaining_blance').val(remainingBalance.toFixed(2));
            }

            // Add item to the table
            $('#addItem').on('click', function() {
                const item = $('#item option:selected').text();
                const itemId = $('#item').val();
                const hsn = $('#item option:selected').data('hsn');
                const variation = $('#item option:selected').data('variation');
                const taxRate = parseFloat($('#item option:selected').data('tax'));
                const qty = $('#qty').val();
                alert(qty);
                const amountPerUnit = parseFloat($('#amount').val());

                if (itemId && !isNaN(qty) && !isNaN(amountPerUnit)) {
                    const totalAmount = qty * amountPerUnit;
                    const tax = totalAmount * (taxRate / 100);
                    const totalWithTax = totalAmount + tax;

                    itemCount++;
                    amountBeforeTax += totalAmount;
                    totalTax += tax; // Update total tax
                    grandTotal += totalWithTax;

                    $('#amount_before_tax').val(amountBeforeTax.toFixed(2));

                    const row = `
                    <tr>
                        <td>${itemCount}</td>
                        <td>${item}<input type="hidden" name="items[]" value="${itemId}"></td>
                        <td>${qty}<input type="hidden" name="quantities[]" value="${qty}"></td>
                        <td>${hsn}</td>
                        <td>${variation}</td>
                        <td>${amountPerUnit.toFixed(2)}<input type="hidden" name="rates[]" value="${amountPerUnit.toFixed(2)}"></td>
                        <td>${taxRate} %<input type="hidden" name="taxes[]" value="${tax.toFixed(2)}"></td>
                        <td>${totalAmount.toFixed(2)}<input type="hidden" name="totalAmounts[]" value="${totalAmount.toFixed(2)}"></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
                    </tr>
                `;
                    $('#itemsTable tbody').append(row);

                    var companyStateValue = $('#companyState').val();
                    var selectedState = $('#vendor option:selected').data('state');

                    // Update the total tax and grand total fields
                    if (companyStateValue == selectedState) {
                        var cgst = totalTax / 2;
                        $('#cgst').val(cgst.toFixed(2));
                        $('#sgst').val(cgst.toFixed(2));
                    } else {
                        $('#igst').val(totalTax.toFixed(2));
                    }
                    updateGrandTotal();

                    // Clear the input fields after adding the item
                    $('#item').val('').trigger('change');
                    $('#qty').val('');
                    $('#amount').val('');
                } else {
                    setFlash("error", "Please fill all fields with valid values.");
                }
            });

            // Remove item from the table
            $(document).on('click', '.removeItem', function() {
                const taxToRemove = parseFloat($(this).closest('tr').find('input[name="taxes[]"]').val());
                const amountToRemove = parseFloat($(this).closest('tr').find('input[name="totalAmounts[]"]').val());

                totalTax -= taxToRemove; // Subtract the removed tax from total tax
                grandTotal -= amountToRemove + taxToRemove; // Subtract the removed amount from grand total
                amountBeforeTax -= amountToRemove; // Subtract the removed amount from total amount before tax

                $(this).closest('tr').remove();
                itemCount--;
                updateSNo();

                // Recalculate tax after removing the item
                recalculateTax();

                updateGrandTotal();

                // If no items remain, reset all fields to 0
                if ($('#itemsTable tbody tr').length === 0) {
                    resetAllFields();
                }
            });


            // Function to recalculate total tax
            function recalculateTax() {
                totalTax = 0;
                $('#itemsTable tbody tr').each(function() {
                    const rowTax = parseFloat($(this).find('input[name="taxes[]"]').val()) || 0;
                    totalTax += rowTax;
                });

                var companyStateValue = $('#companyState').val();
                var selectedState = $('#vendor option:selected').data('state');

                if (companyStateValue == selectedState) {
                    var cgst = totalTax / 2;
                    $('#cgst').val(cgst.toFixed(2));
                    $('#sgst').val(cgst.toFixed(2));
                    $('#igst').val('0.00');
                } else {
                    $('#igst').val(totalTax.toFixed(2));
                    $('#cgst').val('0.00');
                    $('#sgst').val('0.00');
                }
            }

            // Reset all fields when no items are present
            function resetAllFields() {
                $('#cgst').val('0.00');
                $('#sgst').val('0.00');
                $('#igst').val('0.00');
                $('#total_tax').val('0.00');
                $('#amount_before_tax').val('0.00');
                $('#grand_total').val('0.00');
                $('#other_expense').val('0.00');
                $('#discount').val('0.00');
                $('#round_off').val('0.00');
                $('#received_amount').val('0.00');
                $('#balance_amount').val('0.00');

                // Reset any other necessary fields
                totalTax = 0.00;
                grandTotal = 0.00;
                amountBeforeTax = 0.00;
            }

            // Update S. No. after item removal
            function updateSNo() {
                $('#itemsTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Listen to changes in other expenses, discount, and round off fields to update grand total
            $('#other_expense, #discount, #round_off').on('input', function() {
                updateGrandTotal();
            });

            // Update remaining balance when given amount changes
            $('#given_amount').on('input', function() {
                updateRemainingBalance();
            });

            // Validate before form submission
            $('form').on('submit', function(e) {
                if ($('#itemsTable tbody tr').length === 0) {
                    setFlash("error", "Please add at least one item to the purchase.");
                    e.preventDefault(); // Prevent form submission
                    return;
                }

                let valid = true;
                $('#itemsTable tbody tr').each(function() {
                    if ($(this).find('input[name="items[]"]').length === 0 ||
                        $(this).find('input[name="quantities[]"]').length === 0 ||
                        $(this).find('input[name="rates[]"]').length === 0 ||
                        $(this).find('input[name="taxes[]"]').length === 0 ||
                        $(this).find('input[name="totalAmounts[]"]').length === 0) {
                        valid = false;
                    }
                });

                if (!valid) {
                    setFlash("error",
                        "Please ensure all fields (items, quantities, rates, taxes, total amounts) are filled in each row."
                    );
                    e.preventDefault(); // Prevent form submission
                }
            });

            // Flash message function using Toast.fire
            function setFlash(type, message) {
                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // Ensure only valid numbers are entered in round off field
            $('#round_off').on('input', function() {
                this.value = this.value.replace(/[^0-9.-]/g, '');
            });

            // Handle 'Add Vendor' form submission via AJAX
            $('#AddItemven').click(function (e) {
                e.preventDefault(); // Prevent default form submission behavior

                // Collect form data
                let data = {
                    full_name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    city: $('#city').val(),
                    state: $('#state').val(),
                    zipcode: $('#zipcode').val(),
                    address: $('#address').val(),
                    gst: $('#gst').val(),
                    role: $('#role').val(),
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                };

                // Clear previous validation error messages
                $('.error-text').text('');

                // Send data via AJAX POST request
                $.ajax({
                    url: '{{ route('company.vendor.store') }}', // URL to store vendor data
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            setFlash("success", response.message); // Show success message
                            $('#addModal').modal('hide'); // Close modal after successful save
                            $('#addModal').find('input, textarea, select').val(''); // Reset form fields
                            location.reload();
                        } else {
                            // Display validation errors if any
                            if (response.errors) {
                                for (let field in response.errors) {
                                    let $field = $(`#${field}`);
                                    if ($field.length) {
                                        $field.siblings('.error-text').text(response.errors[field][0]);
                                    }
                                }
                            } else {
                                setFlash("error", response.message); // Show error message
                            }
                        }
                    },
                    error: function (xhr) {
                        setFlash("error", "An unexpected error occurred."); // Handle general errors
                    }
                });
            });
            // Event handling for dynamic state and city selection
            $(document).ready(function () {
                // Trigger when state is changed in the 'Add Vendor' modal
                $('#state').on('change', function () {
                    let stateId = $('#state').find(':selected').attr('data-id');
                    fetchCities(stateId, $('#city')); // Fetch cities based on selected state
                });

                // Trigger when city is changed in the 'Add Vendor' modal
                $('#city').on('change', function () {
                    let cityId = $('#city').find(':selected').attr('data-id');
                    fetchPincodes(cityId, $('#zipcode')); // Fetch pincodes based on selected city
                });

                // Trigger when state is changed in the 'Edit Vendor' modal
                $('#editstate').on('change', function () {
                    let stateId = $('#editstate').find(':selected').attr('data-id');
                    fetchCities(stateId, $('#editcity')); // Fetch cities based on selected state
                });

                // Trigger when city is changed in the 'Edit Vendor' modal
                $('#editcity').on('change', function () {
                    let cityId = $('#editcity').find(':selected').attr('data-id');
                    fetchPincodes(cityId, $('#editzipcode')); // Fetch pincodes based on selected city
                });

                // Function to fetch cities based on stateId
                function fetchCities(stateId, cityElement) {
                    if (stateId) {
                        $.ajax({
                            url: '{{ route("ajax.getCities", "") }}/' + stateId, // Fetch cities based on state ID
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                cityElement.empty().append('<option selected>Select City</option>');
                                $.each(data, function (key, value) {
                                    cityElement.append('<option value="' + value.city_name + '" data-id="' + value.id + '">' + value.city_name + '</option>');
                                });
                            }
                        });
                    }
                }

                // Function to fetch pincodes based on cityId
                function fetchPincodes(cityId, zipcodeElement) {
                    if (cityId) {
                        $.ajax({
                            url: '{{ route("ajax.getPincodes", "") }}/' + cityId, // Fetch pincodes based on city ID
                            type: 'GET',
                            dataType: 'json',
                            success: function (datapincode) {
                                zipcodeElement.empty().append('<option selected>Select Pincode</option>');
                                $.each(datapincode, function (keypincode, valuepincode) {
                                    zipcodeElement.append('<option value="' + valuepincode.pincode + '">' + valuepincode.pincode + '</option>');
                                });
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
