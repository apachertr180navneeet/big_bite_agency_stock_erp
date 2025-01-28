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
                                <!-- customer Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="customer" class="form-label">Customer</label>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Customer</button>
                                    <select class="form-select" id="customer" name="customer">
                                        <option value="">Select</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer') == $customer->id ? 'selected' : '' }}
                                                data-state="{{ $customer->state }}">{{ $customer->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Transport Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Place Of Supply</label>
                                    <input type="text" class="form-control" id="weight" name="weight"
                                        value="{{ old('weight') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Transport</label>
                                    <input type="text" class="form-control" id="transport" name="transport"
                                        value="{{ old('transport') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Vehicle No.</label>
                                    <input type="text" class="form-control" id="vehicle_no" name="vehicle_no"
                                        value="{{ old('vehicle_no') }}">
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
                                        <option value="">Select</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-tax="{{ $item->tax_rate }}"
                                                data-variation="{{ $item->variation_name }}" data-hsn="{{ $item->hsn_hac }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <label for="igst" class="form-label text-end">CGST/SGST</label>
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
                                        name="other_expense" min="0">
                                </div>
                            </div>
                            <!-- Discount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="discount" class="form-label text-end">Discount(-)</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="discount" value="0"
                                        name="discount" min="0">
                                </div>
                            </div>
                            <!-- Round Off -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="round_off" class="form-label text-end">Round Off</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" id="round_off" value="0"
                                        name="round_off">
                                </div>
                            </div>
                            <!-- Total Invoice value -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="grand_total" class="form-label text-end">Total Invoice value</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="grand_total" value="0"
                                        name="grand_total" min="0" readonly>
                                </div>
                            </div>
                            <!-- Given Amount -->
                            <div class="row">
                                <div class="col-md-3 mb-3"></div>
                                <div class="col-md-5 mb-3">
                                    <label for="received_amount" class="form-label text-end">Received Amount</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control" id="received_amount"
                                        name="received_amount" value="0" min="0">
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
                                    <input type="number" class="form-control" id="balance_amount" name="balance_amount"
                                        value="0" min="0" readonly>
                                    @error('balance_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="card-body text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
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
                    <h5 class="modal-title" id="exampleModalLabel1">Customer Add</h5>
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
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="hidden" id="role" value="customer">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="AddItem">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            // Variables for tracking totals
            let itemCount = 0;
            let totalTax = 0;
            let grandTotal = 0;
            let amountBeforeTax = 0;

            // Vendor change handler
            $('#customer').on('change', function() {
                // Get the selected vendor state and company state
                const selectedState = $('#customer option:selected').data('state');
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

            // Function to update the grand total field based on other expenses, discounts, and round off values
            function updateGrandTotal() {
                const otherExpense = parseFloat($('#other_expense').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                const roundOff = parseFloat($('#round_off').val()) || 0;
                const calculatedTotal = grandTotal + otherExpense - discount + roundOff;
                $('#grand_total').val(calculatedTotal.toFixed(2));
            }

            // Function to update remaining balance based on received amount and grand total
            function updateRemainingBalance() {
                const givenAmount = parseFloat($('#received_amount').val()) || 0;
                const calculatedTotalMain = parseFloat($('#grand_total').val()) || 0;
                const remainingBalance = calculatedTotalMain - givenAmount;
                $('#balance_amount').val(remainingBalance.toFixed(2));
            }

            // Add item to the table
            $('#addItem').on('click', function() {
                const item = $('#item option:selected').text();
                const itemId = $('#item').val();
                const variation = $('#item option:selected').data('variation');
                const hsn = $('#item option:selected').data('hsn');
                const taxRate = parseFloat($('#item option:selected').data('tax'));
                const qty = $('#qty').val();
                const amountPerUnit = parseFloat($('#amount').val());

                // Check if the item ID, quantity, and amount per unit are valid
                if (itemId && !isNaN(qty) && !isNaN(amountPerUnit)) {

                    let itemExists = false;
                    $('#itemsTable tbody tr').each(function() {
                        const existingItemId = $(this).find('input[name="items[]"]').val();
                        if (existingItemId == itemId) {
                            itemExists = true; // If the item exists, set the flag to true
                            return false; // Exit the loop early if a match is found
                        }
                    });

                    if (itemExists) {
                        // Show error if the item already exists
                        setFlash('error',
                            'This item has already been added. Please select a different item.');
                    } else {

                        const totalAmount = qty * amountPerUnit;
                        const tax = totalAmount * (taxRate / 100);
                        const totalWithTax = totalAmount + tax;

                        // Update item count, amounts, and taxes
                        itemCount++;
                        amountBeforeTax += totalAmount;
                        totalTax += tax;
                        grandTotal += totalWithTax;

                        // Update amount before tax in the form
                        $('#amount_before_tax').val(amountBeforeTax.toFixed(2));

                        // Generate a new row for the item in the table
                        const row = `
                                        <tr>
                                            <td>${itemCount}</td>
                                            <td>${item}<input type="hidden" name="items[]" value="${itemId}"></td>
                                            <td>${qty}<input type="hidden" name="quantities[]" value="${qty}"></td>
                                            <td>${hsn}</td>
                                            <td>${variation}</td>
                                            <td>${amountPerUnit.toFixed(2)}<input type="hidden" name="rates[]" value="${amountPerUnit.toFixed(2)}"></td>
                                            <td>${taxRate}%<input type="hidden" name="taxes[]" value="${tax.toFixed(2)}"></td>
                                            <td>${totalAmount.toFixed(2)}<input type="hidden" name="totalAmounts[]" value="${totalAmount.toFixed(2)}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
                                        </tr>
                                    `;
                        $('#itemsTable tbody').append(row);

                        // Determine tax display based on company and customer states
                        var companyStateValue = $('#companyState').val();
                        var selectedState = $('#customer option:selected').data('state');

                        // If company and customer states are the same, apply IGST; otherwise, apply CGST and SGST
                        if (companyStateValue != selectedState) {
                            $('#igst').val(totalTax.toFixed(2));
                        } else {
                            const cgst = totalTax / 2;
                            $('#cgst').val(cgst.toFixed(2));
                            $('#sgst').val(cgst.toFixed(2));
                        }

                        // Update grand total
                        updateGrandTotal();

                        // Clear input fields after adding item
                        $('#item').val('').trigger('change');
                        $('#qty').val('');
                        $('#amount').val('');
                    }
                } else {
                    setFlash("error", "Please fill all fields with valid values.");
                }
            });

            // Remove item from the table and update totals
            $(document).on('click', '.removeItem', function() {
                const taxToRemove = parseFloat($(this).closest('tr').find('input[name="taxes[]"]')
                    .val());
                const amountToRemove = parseFloat($(this).closest('tr').find(
                        'input[name="totalAmounts[]"]')
                    .val());

                // Subtract the tax and amount from total values
                totalTax -= taxToRemove;
                grandTotal -= amountToRemove;
                amountBeforeTax -= amountToRemove

                // Remove the row and update the item count and serial numbers
                $(this).closest('tr').remove();
                itemCount--;
                updateSNo();

                // Update tax and grand total after removing an item
                $('#total_tax').val(totalTax.toFixed(2));
                $('#amount_before_tax').val(amountBeforeTax.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));

                // Recalculate tax after removing the item
                recalculateTax();

                updateGrandTotal();

                // Check if there are no items left in the table
                if ($('#itemsTable tbody tr').length === 0) {
                    resetAllFields();
                }
            });

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

            //function to recalculate all tax
            function recalculateTax() {
                totalTax = 0;
                $('#itemsTable tbody tr').each(function() {
                    const rowTax = parseFloat($(this).find('input[name="taxes[]"]').val()) || 0;
                    totalTax += rowTax;
                });

                var companyStateValue = $('#companyState').val();
                var selectedState = $('#customer option:selected').data('state');

                // If company and customer states are the same, apply IGST; otherwise, apply CGST and SGST
                if (companyStateValue == selectedState) {
                    $('#igst').val(totalTax.toFixed(2));
                } else {
                    const cgst = totalTax / 2;
                    $('#cgst').val(cgst.toFixed(2));
                    $('#sgst').val(cgst.toFixed(2));
                }

            }

            // Update serial numbers after item removal
            function updateSNo() {
                $('#itemsTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Listen to changes in other expenses, discount, and round off fields to update the grand total and remaining balance
            $('#other_expense, #discount, #round_off').on('input', function() {
                updateGrandTotal();
                updateRemainingBalance();
            });

            // Update remaining balance when the received amount changes
            $('#received_amount').on('input', function() {
                updateRemainingBalance();
            });

            // Form submission validation to ensure at least one item is added
            $('#coustomer_add').on('submit', function(e) {
                if ($('#itemsTable tbody tr').length === 0) {
                    e.preventDefault();
                    setFlash('error', 'Please add at least one item to the sales book.');
                    return false;
                }
            });

            // Flash message function using Toast for displaying messages
            function setFlash(type, message) {
                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // AJAX call to check stock availability for the selected item and quantity
            $('#qty').on('input', function() {
                const itemId = $('#item').val();
                const qty = $(this).val();

                if (itemId && qty > 0) {
                    $.ajax({
                        url: '{{ route('ajax.checkStock') }}', // Define your route here
                        method: 'POST',
                        data: {
                            item_id: itemId,
                            quantity: qty,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            if (response.stock_available) {
                                $('#addItem').prop('disabled', false).text('Add Item');
                            } else {
                                $('#addItem').prop('disabled', true).text('Out of Stock');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred.');
                        }
                    });
                } else {
                    $('#addItem').prop('disabled', true).text('Add Item');
                }
            });

            $('#AddItem').click(function(e) {
                e.preventDefault();

                // Collect form data
                let data = {
                    full_name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val(),
                    city: $('#city').val(),
                    state: $('#state').val(),
                    role: $('#role').val(),
                    address: $('#address').val(),
                    zipcode: $('#zipcode').val(),
                    gst: $('#gst').val(),
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                };


                // Clear previous validation error messages
                $('.error-text').text('');

                $.ajax({
                    url: '{{ route('company.customer.store') }}', // Adjust the route as necessary
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            setFlash("success", response.message);
                            $('#addModal').modal('hide'); // Close the modal
                            $('#addModal').find('input, textarea, select').val(''); // Reset form fields
                             // Reload DataTable
                             location.reload();
                        } else {
                            // Display validation errors
                            if (response.errors) {
                                for (let field in response.errors) {
                                    let $field = $(`#${field}`);
                                    if ($field.length) {
                                        $field.siblings('.error-text').text(response.errors[field][0]);
                                    }
                                }
                            } else {
                                setFlash("error", response.message);
                            }
                        }
                    },
                    error: function(xhr) {
                        setFlash("error", "An unexpected error occurred.");
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
