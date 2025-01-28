@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Tax</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Tax
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="variationTable">
                            <thead>
                                <tr>
                                    <th>Rate</th>
                                    <th>Name</th>
                                    <th>Status</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">Tax Add</h5>
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
                        <label for="rate" class="form-label">Rate</label>
                        <input type="text" id="rate" class="form-control" placeholder="Enter Rate" />
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="AddVariation">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Tax Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" id="compid">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="editname" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="rate" class="form-label">Rate</label>
                        <input type="text" id="editrate" class="form-control" placeholder="Enter Rate" />
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="EditUser">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.tax.getall') }}",
                type: 'GET',
            },
            columns: [
                { data: "rate" },
                { data: "name" },
                {
                    data: "status",
                    render: (data, type, row) => {
                        if (row.status === 'active') {
                            return '<span class="badge bg-label-success me-1">Active</span>';
                        }
                        if (row.status === 'inactive') {
                            return '<span class="badge bg-label-danger me-1">Inactive</span>';
                        }
                        return '';
                    }
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        const statusButton = row.status === "inactive"
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        return `${statusButton} ${deleteButton} ${editButton}`;
                    },
                },
            ],
        });

        $('#AddVariation').click(function(e) {
            e.preventDefault();

            // Collect form data
            let data = {
                name: $('#name').val(),
                rate: $('#rate').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            // Clear previous validation error messages
            $('.error-text').text('');

            $.ajax({
                url: '{{ route('company.tax.store') }}',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide');
                        $('#addModal').find('input').val('');
                        table.ajax.reload();
                    } else {
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
                error: function() {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Define editUser function
        window.editUser = function(userId) {
            const url = '{{ route("company.tax.get", ":userid") }}'.replace(":userid", userId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#compid').val(data.id);
                    $('#editname').val(data.name);
                    $('#editrate').val(data.rate);

                    $('#editModal').modal('show');
                    setFlash("success", 'Tax found successfully.');
                },
                error: function() {
                    setFlash("error", "Tax not found. Please try again later.");
                }
            });
        };

        // Handle form submission for editing
        $('#EditUser').on('click', function() {
            const userId = $('#compid').val();
            $.ajax({
                url: '{{ route('company.tax.update') }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: $('#editname').val(), // Ensure consistency in field names
                    rate: $('#editrate').val(), // Ensure consistency in field names
                    id: userId
                },
                success: function(response) {
                    if (response.success == true) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide');
                        $('#editModal').find('input, textarea, select').val('');
                        table.ajax.reload();
                    } else {
                        // Clear previous errors
                        $('#editModal').find('.error-text').text('');

                        // Display new errors
                        for (let field in response.success) {
                            let $field = $(`#edit${field}`); // Adjust the selector if necessary
                            if ($field.length) {
                                $field.siblings('.error-text').text(response.errors[field][0]);
                            }
                        }
                    }
                },
                error: function() {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Update user status
        window.updateUserStatus = function(userId, status) {
            const message = status === "active" ? "Tax will be able to log in after activation." : "Tax will not be able to log in after deactivation.";

            Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('company.tax.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                const successMessage = status === "active" ? "Tax activated successfully." : "Tax deactivated successfully.";
                                setFlash("success", successMessage);
                            } else {
                                setFlash("error", "There was an issue changing the status. Please contact your system administrator.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
                        },
                    });
                } else {
                    table.ajax.reload();
                }
            });
        };

        // Delete user
        window.deleteUser = function(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Tax?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("company.tax.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "Tax deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the tax. Please contact your system administrator.");
                            }
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
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
    });
</script>
@endsection
