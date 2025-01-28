@extends('admin.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Users For {{ $company->name }}</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add User
            </button>
        </div>
    </div>
    <input type="hidden" name="{{ $comId }}" id="compIdindex">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>City</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">User Add</h5>
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
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="" />
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="AddUser">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">User Edit</h5>
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
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="editemail" class="form-control" placeholder="xxxx@xxx.xx" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="editphone" class="form-control" placeholder="" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="editstate">
                            <option selected>Select  State</option>
                            @foreach ($states as $state)
                                <option value="{{$state->state_name}}" data-id="{{$state->state_id}}">{{$state->state_name}}</option>
                            @endforeach
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="editcity">
                            <option selected>Select  City</option>
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="zipcode" class="form-label">Pincode</label>
                        <select class="form-select" id="editzipcode">
                            <option selected>Select  Pincode</option>
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                    {{--  <div class="col-md-12 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" id="editpassword" class="form-control" placeholder="" />
                        <small class="error-text text-danger"></small>
                    </div>  --}}
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
        const table = $('#usersTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('admin.user.alluser') }}",
                type: 'GET',
            },
            columns: [
                {
                    data: "full_name",
                },
                {
                    data: "email",
                },
                {
                    data: "phone",
                },
                {
                    data: "city"
                },
                {
                    data: "status",
                    render: (data, type, row) => {
                        if (row.status == 'active') {
                            return '<span class="badge bg-label-success me-1">Active</span>';
                        }
                        if (row.status == 'inactive') {
                            return '<span class="badge bg-label-danger me-1">Inactive</span>';
                        }
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

        // Handle form submission via AJAX
        $('#AddUser').click(function(e) {
            e.preventDefault();

            // Collect form data
            let data = {
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zipcode: $('#zipcode').val(),
                password: $('#password').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
            };

            // Clear previous validation error messages
            $('.error-text').text('');

            $.ajax({
                url: '{{ route('admin.user.store') }}', // Adjust the route as necessary
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#addModal').modal('hide'); // Close the modal
                        $('#addModal').find('input, textarea, select').val(''); // Reset form fields
                        table.ajax.reload(); // Reload DataTable
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
            const url = '{{ route("admin.user.get", ":userid") }}'.replace(":userid", userId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    const company = data.company;
                    const cities = data.cities;
                    const pincodes = data.pincodes;

                    $('#compid').val(company.id);
                    $('#editname').val(company.full_name);
                    $('#editemail').val(company.email);
                    $('#editphone').val(company.phone);
                    $('#editcity').val(company.city);
                    $('#editstate').val(company.state);
                    $('#editzipcode').val(company.zipcode);
                    $('#editpassword').val('');

                    // Populate city dropdown
                    $('#editcity').empty().append('<option selected>Select City</option>');
                    cities.forEach(city => {
                        $('#editcity').append(`<option value="${city.city_name}" ${company.city === city.city_name ? 'selected' : ''}>${city.city_name}</option>`);
                    });

                    // Populate pincode dropdown
                    $('#editzipcode').empty().append('<option selected>Select Pincode</option>');
                    pincodes.forEach(pincode => {
                        $('#editzipcode').append(`<option value="${pincode.pincode}" ${company.zipcode === pincode.pincode ? 'selected' : ''}>${pincode.pincode}</option>`);
                    });

                    $('#editModal').modal('show');
                    setFlash("success", 'User found successfully.');
                },
                error: function(xhr) {
                    setFlash("error", "User not found. Please try again later.");
                }
            });
        }

        // Handle form submission for editing
        $('#EditUser').on('click', function() {
            const userId = $('#compid').val();
            $.ajax({
                url: '{{ route('admin.user.update') }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    full_name: $('#editname').val(),
                    email: $('#editemail').val(),
                    phone: $('#editphone').val(),
                    address: $('#editaddress').val(),
                    city: $('#editcity').val(),
                    state: $('#editstate').val(),
                    zipcode: $('#editzipcode').val(),
                    id: userId
                },
                success: function(response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide');
                        $('#editModal').find('input, textarea, select').val(''); // Reset form fields
                        table.ajax.reload();
                    } else {
                        setFlash("error", response.message);
                    }
                },
                error: function(xhr) {
                    setFlash("error", "An unexpected error occurred.");
                }
            });
        });

        // Update user status
        function updateUserStatus(userId, status) {
            const message = status === "active" ? "Company will be able to log in after activation." : "Company will not be able to log in after deactivation.";

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
                        url: "{{ route('admin.user.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            if (response.success) {
                                const successMessage = status === "active" ? "User activated successfully." : "User deactivated successfully.";
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
        }

        // Delete user
        function deleteUser(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this User?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("admin.user.destroy", ":userId") }}'.replace(":userId", userId);
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
                            table.ajax.reload();
                        },
                        error: function () {
                            setFlash("error", "There was an issue processing your request. Please try again later.");
                        },
                    });
                }
            });
        }

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

</script>
@endsection
