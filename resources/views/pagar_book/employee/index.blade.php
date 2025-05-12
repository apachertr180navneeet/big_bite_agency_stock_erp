@extends('pagar_book.layouts.app') @section('style') @endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Employee</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Employee
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
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Date Of Joing</th>
                                    <th>Base Salary</th>
                                    <th>QR CODE</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">Employee Add</h5>
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
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" class="form-control" placeholder="Enter Phone" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="doj" class="form-label">Date Joining</label>
                        <input type="date" id="doj" class="form-control" placeholder="Enter Date Joining" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="base_salary" class="form-label">Base Salary</label>
                        <input type="text" id="base_salary" class="form-control" placeholder="Enter Base Salary" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="qr_scan" class="form-label">QR Scan</label>
                        <input type="file" id="qr_scan" class="form-control" placeholder="Enter Base Salary" />
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
                <h5 class="modal-title" id="exampleModalLabel1">Item Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" id="compid">
                        <label for="editname" class="form-label">Name</label>
                        <input type="text" id="editname" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="hidden" id="compid">
                        <label for="editphone" class="form-label">Phone</label>
                        <input type="text" id="editphone" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="hidden" id="compid">
                        <label for="editdoj" class="form-label">Name</label>
                        <input type="date" id="editdoj" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="hidden" id="compid">
                        <label for="editbaseslary" class="form-label">Name</label>
                        <input type="text" id="editbaseslary" class="form-control" placeholder="Enter Name" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="editqrscan" class="form-label">QR Scan</label>
                        <input type="file" id="editqrscan" class="form-control" placeholder="Enter Base Salary" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <img src="" id="qrscanimage" alt="" style="width: 50%;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="EditComapany">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection @section('script')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = $("#ItemTable").DataTable({
            processing: true,
            ajax: {
                url: "{{ route('pagar.book.employee.getall') }}",
            },
            columns: [
                {
                    data: "full_name",
                },
                {
                    data: "phone",
                },
                {
                    data: "date_of_joing",
                },
                {
                    data: "base_salary",
                },
                 {
                data: "qr_scan", // Assuming 'image_url' is the key containing the image URL
                    render: (data, type, row) => {
                        if (data) {
                            return `<img src="${data}" alt="Employee Image" style="width:50%" />`;
                        } else {
                            return `<span>No Image</span>`;
                        }
                    },
                },
                {
                    data: "status",
                    render: (data, type, row) => {
                        const statusBadge = row.status === "active" ?
                            '<span class="badge bg-label-success me-1">Active</span>' :
                            '<span class="badge bg-label-danger me-1">Inactive</span>';
                        return statusBadge;
                    },
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        const getaddvanceurl = '{{ route("pagar.book.employee.get.advance", ":userId") }}'.replace(":userId", row.id);
                        const getsalaryurl = '{{ route("pagar.book.employee.get.salary", ":userId") }}'.replace(":userId", row.id);
                        const statusButton = row.status === "inactive"
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                        //const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        const getAdvanceButton = `<a href="${getaddvanceurl}" class="btn btn-sm btn-success">Advance</a>`;
                        const getsalaryurlButton = `<a href="${getsalaryurl}" class="btn btn-sm btn-info">Salary</a>`;

                        return `${statusButton} ${editButton} ${getAdvanceButton} ${getsalaryurlButton}`;
                    },
                },

            ],
        });

        // Handle form submission via AJAX
        $('#AddItem').click(function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('phone', $('#phone').val());
            formData.append('doj', $('#doj').val());
            formData.append('base_salary', $('#base_salary').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            let file = $('#qr_scan')[0].files[0];
            if (file) {
                formData.append('qr_scan', file);
            }

            $('.error-text').text(''); // Clear previous validation errors

            $.ajax({
                url: '{{ route('pagar.book.employee.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide');
                        $('#addModal').find('input, textarea, select').val('');
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
                error: function(xhr) {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });


        // Define editUser function
        function editUser(userId) {
            const url = '{{ route("pagar.book.employee.get", ":userid") }}'.replace(":userid", userId);
            
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    const user = data;
                    console.log(user);

                    // Populate form fields
                    $('#compid').val(user.id);
                    $('#editname').val(user.full_name);
                    $('#editphone').val(user.phone);
                    $('#editdoj').val(user.date_of_joing);
                    $('#editbaseslary').val(user.base_salary);

                    // Set QR scan image preview
                    if (user.qr_scan) {
                        // Assuming the image path is stored and accessible via URL like '/uploads/qr_scans/'
                        $('#qrscanimage').attr('src', '' + user.qr_scan).show();
                    } else {
                        $('#qrscanimage').attr('src', '').hide();
                    }

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
            const formData = new FormData();
            const file = $('#editqrscan')[0].files[0];

            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('id', $('#compid').val());
            formData.append('name', $('#editname').val());
            formData.append('phone', $('#editphone').val());
            formData.append('date_of_joing', $('#editdoj').val());
            formData.append('base_salary', $('#editbaseslary').val());

            if (file) {
                formData.append('qr_scan', file);
            }

            $.ajax({
                url: '{{ route("pagar.book.employee.update") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide');
                        $('#editModal').find('input, textarea, select').val('');
                        table.ajax.reload();

                        // If image returned, update preview
                        if (response.user && response.user.qr_scan) {
                            $('#qrscanimage').attr('src', '/uploads/qr_scans/' + response.user.qr_scan);
                        }
                    } else {
                        console.error('Error updating Item data:', response.errors || response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error updating Item data:', xhr);
                }
            });
        });


        // Update user status
        function updateUserStatus(userId, status) {
            const message = status === "active" ? "Item will be able to log in after activation." : "Item will not be able to log in after deactivation.";

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
                        url: "{{ route('pagar.book.employee.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log(response);
                            if (response.success == true) {
                                const successMessage = status === "active" ? "Item activated successfully." : "Item deactivated successfully.";
                                setFlash("success", successMessage);
                            } else {
                                setFlash("error", "There was an issue changing the status. Please contact your system administrator.");
                            }
                            table.ajax.reload(); // Reload DataTable
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
                        },
                    });
                } else {
                    table.ajax.reload(); // Reload DataTable
                }
            });
        };

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
                    const url = '{{ route("pagar.book.employee.destroy", ":userId") }}'.replace(":userId", userId);
                    $.ajax({
                        type: "DELETE",
                        url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                setFlash("success", "User deleted successfully.");
                            } else {
                                setFlash("error", "There was an issue deleting the user. Please contact your system administrator.");
                            }
                            table.ajax.reload(); // Reload DataTable
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

        // Expose functions to global scope
        window.updateUserStatus = updateUserStatus;
        window.deleteUser = deleteUser;
        window.editUser = editUser;
    });
</script>
@endsection
