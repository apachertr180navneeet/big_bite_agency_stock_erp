@extends('company.layouts.app') @section('style')
    @endsection @section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 text-start">
                <h5 class="py-2 mb-2">
                    <span class="text-primary fw-light">Bank And Cash Mangement</span>
                </h5>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Bank And Cash
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" id="ItemTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Serial No.</th>
                                        <th>Particular</th>
                                        <th>Amount</th>
                                        <th>Deposit in</th>
                                        <th>Withdraw From</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Contra Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="Date" id="date" class="form-control" placeholder="Enter Date" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="serial_no" class="form-label">Serial No.</label>
                            <input type="text" id="serial_no" class="form-control" placeholder="Enter Serial No." />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deposite_in" class="form-label">Deposit in</label>
                            <select id="deposite_in" class="form-select form-select">
                                <option value="">Select</option>
                                @foreach ($bankLists as $bankList)
                                    <option value="{{ $bankList->id }}">{{ $bankList->bank_name }}</option>
                                @endforeach
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="withdraw_in" class="form-label">Withdraw From</label>
                            <select id="withdraw_in" class="form-select form-select">
                                <option value="">Select</option>
                                @foreach ($bankLists as $bankList)
                                    <option value="{{ $bankList->id }}">{{ $bankList->bank_name }}</option>
                                @endforeach
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="particular" class="form-label">Particular</label>
                            <input type="text" id="particular" class="form-control" placeholder="Enter Particular" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="text" id="amount" class="form-control" placeholder="Enter Amount" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">description</label>
                            <textarea class="form-control" id="description" rows="3"></textarea>
                            <small class="error-text text-danger"></small>
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

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Contra Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" value="" id="editid">
                        <div class="col-md-6 mb-3">
                            <label for="editdate" class="form-label">Date</label>
                            <input type="Date" id="editdate" class="form-control" placeholder="Enter Date" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_serial_no" class="form-label">Serial No.</label>
                            <input type="text" id="edit_serial_no" class="form-control"
                                placeholder="Enter Serial No." />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_deposite_in" class="form-label">Deposit in</label>
                            <select id="edit_deposite_in" class="form-select form-select">
                                <option value="">Select</option>
                                @foreach ($bankLists as $bankList)
                                    <option value="{{ $bankList->id }}" selected="selected">{{ $bankList->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_withdraw_in" class="form-label">Withdraw From</label>
                            <select id="edit_withdraw_in" class="form-select form-select">
                                <option value="">Select</option>
                                @foreach ($bankLists as $bankList)
                                    <option value="{{ $bankList->id }}">{{ $bankList->bank_name }}</option>
                                @endforeach
                            </select>
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_particular" class="form-label">Particular</label>
                            <input type="text" id="edit_particular" class="form-control"
                                placeholder="Enter Amount" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="text" id="edit_amount" class="form-control" placeholder="Enter Amount" />
                            <small class="error-text text-danger"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_description" class="form-label">description</label>
                            <textarea class="form-control" id="edit_description" rows="3"></textarea>
                            <small class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="EditBankAndCash">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endsection @section('script')
    <script>
        $(document).ready(function() {

            const depositeSelect = document.getElementById('deposite_in');
            const withdrawSelect = document.getElementById('withdraw_in');

            function disableOptions(disabledId,selectElement){
                const options = selectElement.options;
                for (let i = 0; i < options.length; i++) {
                    options[i].disabled = options[i].value === disabledId && disabledId !== "";
                }
            }

            depositeSelect.addEventListener('change',function(){
                const selectedDepositValue = this.value;
                disableOptions(selectedDepositValue,withdrawSelect)
            })

            withdrawSelect.addEventListener('change',function(){
                const selectedWithrawalValue = this.value;
                disableOptions(selectedWithrawalValue,depositeSelect);
            })




            // Initialize DataTable
            const table = $("#ItemTable").DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('company.bank.and.cash.getall') }}",
                },
                columns: [{
                        data: "date",
                    },
                    {
                        data: "serial_no",
                    },
                    {
                        data: "particular",
                    },
                    {
                        data: "amount",
                    },
                    {
                        data: "deposite_bank_name",
                    },
                    {
                        data: "withdraw_bank_name",
                    },
                    {
                        data: "description",
                    },
                    {
                        data: "action",
                        render: (data, type, row) => {
                            const deleteButton =
                                `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                            const editButton =
                                `<button type="button" class="btn btn-sm btn-warning" onclick="editbankcash(${row.id})">Edit</button>`;

                            return `${deleteButton} ${editButton}`;
                        },
                    },

                ],
            });

            // Handle form submission via AJAX
            $('#AddItem').click(function(e) {
                e.preventDefault();

                // Collect form data
                let data = {
                    date: $('#date').val(),
                    serial_no: $('#serial_no').val(),
                    amount: $('#amount').val(),
                    deposite_in: $('#deposite_in').val(),
                    withdraw_in: $('#withdraw_in').val(),
                    description: $('#description').val(),
                    particular: $('#particular').val(),
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                };


                // Clear previous validation error messages
                $('.error-text').text('');
                $.ajax({
                    url: '{{ route('company.bank.and.cash.store') }}', // Adjust the route as necessary
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            setFlash("success", response.message);
                            $('#addModal').modal('hide'); // Close the modal
                            $('#addModal').find('input, textarea, select').val(
                                ''); // Reset form fields
                            table.ajax.reload(); // Reload DataTable
                        } else {
                            // Display validation errors
                            if (response.errors) {
                                for (let field in response.errors) {
                                    let $field = $(`#${field}`);
                                    if ($field.length) {
                                        $field.siblings('.error-text').text(response.errors[
                                            field][0]);
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

            // Define editbankcash function
            function editbankcash(userId) {
                const url = '{{ route('company.bank.and.cash.get', ':userid') }}'.replace(":userid", userId);
                $.ajax({
                    url: url, // Update this URL to match your route
                    method: 'GET',
                    success: function(response) {
                        const data = response.bankCash;
                        const banks = response.banks;

                        // Populate modal fields with the retrieved data
                        $('#editid').val(data.id);
                        $('#editdate').val(data.date);
                        $('#edit_serial_no').val(data.serial_no);
                        // Populate Deposit In dropdown and select the current value
                        let depositOptions = '<option value="">Select</option>';
                        banks.forEach(bank => {
                            depositOptions +=
                                `<option value="${bank.id}" ${bank.id == data.deposite_in ? 'selected' : ''}>${bank.bank_name}</option>`;
                        });
                        $('#edit_deposite_in').html(depositOptions);

                        // Populate Withdraw From dropdown and select the current value
                        let withdrawOptions = '<option value="">Select</option>';
                        banks.forEach(bank => {
                            withdrawOptions +=
                                `<option value="${bank.id}" ${bank.id == data.withdraw_in ? 'selected' : ''}>${bank.bank_name}</option>`;
                        });
                        $('#edit_withdraw_in').html(withdrawOptions);
                        $('#edit_amount').val(data.amount);
                        $('#edit_description').val(data.description);
                        $('#edit_particular').val(data.particular);

                        // Open the modal
                        $('#editModal').modal('show');
                        setFlash("success", 'Item found successfully.');
                    },
                    error: function(xhr) {
                        setFlash("error", "Item not found. Please try again later.");
                    }
                });
            }

            // Handle form submission
            $('#EditBankAndCash').on('click', function() {
                const userId = $('#editid').val(); // Ensure userId is available in the scope
                $.ajax({
                    url: '{{ route('company.bank.and.cash.update') }}', // Update this URL to match your route
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        date: $('#editdate').val(),
                        serial_no: $('#edit_serial_no').val(),
                        deposite_in: $('#edit_deposite_in').val(),
                        withdraw_in: $('#edit_withdraw_in').val(),
                        amount: $('#edit_amount').val(),
                        description: $('#edit_description').val(),
                        particular: $('#edit_particular').val(),
                        id: userId // Ensure userId is in scope or adjust accordingly
                    },
                    success: function(response) {
                        if (response.success) {
                            // Optionally, refresh the page or update the table with new data
                            //table.ajax.reload();
                            setFlash("success", response.message);
                            $('#editModal').modal('hide'); // Close the modal
                            $('#editModal').find('input, textarea, select').val(
                                ''); // Reset form fields
                            table.ajax.reload(); // Reload DataTable
                        } else {
                            console.error('Error updating Item data:', response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating Item data:', xhr);
                    }
                });
            });

            // Delete user
            function deleteUser(userId) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to delete this Item?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = '{{ route('company.bank.and.cash.destroy', ':userId') }}'.replace(":userId",
                            userId);
                        $.ajax({
                            type: "DELETE",
                            url,
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    setFlash("success", "User deleted successfully.");
                                } else {
                                    setFlash("error",
                                        "There was an issue deleting the user. Please contact your system administrator."
                                    );
                                }
                                table.ajax.reload(); // Reload DataTable
                            },
                            error: function() {
                                setFlash("error",
                                    "There was an issue processing your request. Please try again later."
                                );
                            },
                        });
                    }
                });
            };

            // Flash message function using Toast.fire
            function setFlash(type, message) {
                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // Expose functions to global scope
            window.deleteUser = deleteUser;
            window.editbankcash = editbankcash;
        });
    </script>
@endsection
