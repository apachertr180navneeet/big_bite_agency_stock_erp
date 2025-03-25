@extends('pagar_book.layouts.app') @section('style') @endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Salary</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Create salary
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
                                    <th>Month/Year</th>
                                    <th>Deduction</th>
                                    <th>Amount</th>
                                    {{--  <th>Action</th>  --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empSalarys as $empSalary) 
                                    <tr>
                                        <td>{{ $empSalary->slarly_mounth }}</td>
                                        <td>{{ $empSalary->diduction_amount }}</td>
                                        <td>{{ $empSalary->amount }}</td>
                                        {{--  <td></td>  --}}
                                    </tr>
                                @endforeach
                            </tbody>
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
                <h5 class="modal-title" id="exampleModalLabel1">Advance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" name="user_id" id="user_id" value="{{ $userId }}">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" class="form-control" placeholder="Enter Date" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" id="amount" class="form-control" placeholder="Enter Amount" />
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


@endsection @section('script')
<script>
    $(document).ready(function () {
        
        const table = $("#ItemTable").DataTable({
            processing: true,
        });
        // Handle form submission via AJAX
        $('#AddItem').click(function(e) {
            e.preventDefault();

            // Collect form data
            let data = {
                user_id: $('#user_id').val(),
                date: $('#date').val(),
                amount: $('#amount').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
            };


            // Clear previous validation error messages
            $('.error-text').text('');

            $.ajax({
                url: '{{ route('pagar.book.employee.advncestore') }}', // Adjust the route as necessary
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide'); // Close the modal
                        $('#addModal').find('input, textarea, select').val(''); // Reset form fields
                        //table.ajax.reload(); // Reload DataTable
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

        // Define editUser function
        function editUser(userId) {
            const url = '{{ route("pagar.book.employee.get", ":userid") }}'.replace(":userid", userId);
            $.ajax({
                url: url, // Update this URL to match your route
                method: 'GET',
                success: function(data) {
                    const user = data;
                    console.log(user);

                    // Populate modal fields with the retrieved data
                    $('#compid').val(user.id);
                    $('#editname').val(user.full_name);
                    $('#editphone').val(user.phone);
                    $('#editdoj').val(user.date_of_joing);
                    $('#editbaseslary').val(user.base_salary);

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
        $('#EditComapany').on('click', function() {
            const userId = $('#compid').val(); // Ensure userId is available in the scope
            $.ajax({
                url: '{{ route('pagar.book.employee.update') }}', // Update this URL to match your route
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: $('#editname').val(),
                    phone: $('#editphone').val(),
                    date_of_joing: $('#editdoj').val(),
                    base_salary: $('#editbaseslary').val(),
                    id: userId // Ensure userId is in scope or adjust accordingly
                },
                success: function(response) {
                    if (response.success) {
                        // Optionally, refresh the page or update the table with new data
                        //table.ajax.reload();
                        setFlash("success", response.message);
                        $('#editModal').modal('hide'); // Close the modal
                        $('#editModal').find('input, textarea, select').val(''); // Reset form fields
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

        // Flash message function using Toast.fire
        function setFlash(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Expose functions to global scope
        window.editUser = editUser;
    });
</script>
@endsection
