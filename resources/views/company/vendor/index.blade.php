@extends('company.layouts.app') @section('style') @endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Vendor</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Vendor
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
                                    <th>GST No.</th>
                                    <th>Address</th>
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
                <button type="button" class="btn btn-primary" id="AddItem">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Vendor Edit</h5>
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
                        <label for="editaddress" class="form-label">Address</label>
                        <textarea class="form-control" id="editaddress"name="editaddress" rows="3"></textarea>
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
                    <div class="col-md-12 mb-3">
                        <label for="gst" class="form-label">GST No.</label>
                        <input type="text" id="editgst" class="form-control" placeholder="" />
                        <small class="error-text text-danger"></small>
                        <input type="hidden" id="role" value="vendor">
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
            url: "{{ route('company.vendor.getall') }}", // URL to fetch all vendor data
        },
        columns: [
            { data: "full_name" }, // Column for vendor's full name
            { data: "gst_no" }, // Column for vendor's GST number
            { data: "address" }, // Column for vendor's address
            {
                data: "status", // Column for status (Active/Inactive)
                render: (data, type, row) => {
                    // Conditionally display status badge based on the 'status' field
                    return row.status === "active"
                        ? '<span class="badge bg-label-success me-1">Active</span>'
                        : '<span class="badge bg-label-danger me-1">Inactive</span>';
                },
            },
            {
                data: "action", // Column for action buttons (Activate/Deactivate, Delete, Edit)
                render: (data, type, row) => {
                    // Generate action buttons based on row status
                    const statusButton = row.status === "inactive"
                        ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                        : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                    //const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                    const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;

                    // Return combined buttons as HTML string
                    return `${statusButton} ${editButton}`;
                },
            },
        ],
    });

    // Handle 'Add Vendor' form submission via AJAX
    $('#AddItem').click(function (e) {
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
                    table.ajax.reload(); // Reload DataTable to reflect new data
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

    // Function to handle editing vendor
    function editUser(userId) {
        const url = '{{ route("company.vendor.get", ":userid") }}'.replace(":userid", userId); // Build URL with user ID
        $.ajax({
            url: url, // Fetch vendor data
            method: 'GET',
            success: function (data) {

                const user = data.user;
                const cities = data.cities;
                const pincodes = data.pincodes;


                // Populate the 'Edit Vendor' modal with retrieved data
                $('#compid').val(user.id);
                $('#editname').val(user.full_name);
                $('#editemail').val(user.email);
                $('#editphone').val(user.phone);
                $('#editcity').val(user.city);
                $('#editstate').val(user.state);
                $('#editgst').val(user.gst_no);
                $('#editzipcode').val(user.zipcode);
                $('#editaddress').val(user.address);

                // Populate city dropdown
                $('#editcity').empty().append('<option selected>Select City</option>');
                cities.forEach(city => {
                    $('#editcity').append(`<option value="${city.city_name}" ${user.city === city.city_name ? 'selected' : ''}>${city.city_name}</option>`);
                });

                // Populate pincode dropdown
                $('#editzipcode').empty().append('<option selected>Select Pincode</option>');
                pincodes.forEach(pincode => {
                    $('#editzipcode').append(`<option value="${pincode.pincode}" ${user.zipcode === pincode.pincode ? 'selected' : ''}>${pincode.pincode}</option>`);
                });

                // Show edit modal
                $('#editModal').modal('show');
                setFlash("success", 'Vendor found successfully.');
            },
            error: function (xhr) {
                setFlash("error", "Vendor not found. Please try again later."); // Handle errors
            }
        });
    }

    // Handle 'Edit Vendor' form submission
    $('#EditComapany').on('click', function () {
        const userId = $('#compid').val(); // Get the vendor ID from the hidden input field
        $.ajax({
            url: '{{ route('company.vendor.update') }}', // URL to update vendor data
            method: 'POST',
            data: {
                full_name: $('#editname').val(),
                email: $('#editemail').val(),
                phone: $('#editphone').val(),
                city: $('#editcity').val(),
                state: $('#editstate').val(),
                gst_no: $('#editgst').val(),
                zipcode: $('#editzipcode').val(),
                address: $('#editaddress').val(),
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                id: userId // Vendor ID
            },
            success: function (response) {
                if (response.success) {
                    setFlash("success", response.message); // Show success message
                    $('#editModal').modal('hide'); // Close modal after successful update
                    $('#editModal').find('input, textarea, select').val(''); // Reset form fields
                    table.ajax.reload(); // Reload DataTable to reflect updated data
                } else {
                    console.error('Error updating vendor data:', response.message); // Handle errors
                }
            },
            error: function (xhr) {
                console.error('Error updating vendor data:', xhr); // Handle AJAX errors
            }
        });
    });

    // Function to update vendor status
    function updateUserStatus(userId, status) {
        const message = status === "active"
            ? "Vendor will be able to log in after activation."
            : "Vendor will not be able to log in after deactivation.";

        // Confirmation dialog before changing status
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
                // Send AJAX request to update status
                $.ajax({
                    type: "POST",
                    url: "{{ route('company.vendor.status') }}", // URL to update vendor status
                    data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        if (response.success) {
                            const successMessage = status === "active" ? "Vendor activated successfully." : "Vendor deactivated successfully.";
                            setFlash("success", successMessage); // Show success message
                        } else {
                            setFlash("error", "There was an issue changing the status. Please contact your system administrator.");
                        }
                        table.ajax.reload(); // Reload DataTable to reflect status change
                    },
                    error: function () {
                        setFlash("error", "There was an issue processing your request. Please try again later.");
                    }
                });
            } else {
                table.ajax.reload(); // Reload DataTable if action is canceled
            }
        });
    }

    // Function to delete vendor
    function deleteUser(userId) {
        // Confirmation dialog before deletion
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this vendor?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                const url = '{{ route("company.vendor.destroy", ":userId") }}'.replace(":userId", userId); // Build delete URL with vendor ID
                $.ajax({
                    type: "DELETE",
                    url: url, // Send delete request
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        if (response.success) {
                            setFlash("success", "Vendor deleted successfully."); // Show success message
                        } else {
                            setFlash("error", "There was an issue deleting the vendor. Please contact your system administrator.");
                        }
                        table.ajax.reload(); // Reload DataTable to reflect deletion
                    },
                    error: function () {
                        setFlash("error", "There was an issue processing your request. Please try again later.");
                    }
                });
            }
        });
    }

    // Flash message function using Toast
    function setFlash(type, message) {
        Toast.fire({
            icon: type, // Show icon based on type (success, error)
            title: message // Message to display
        });
    }

    // Expose functions to global scope so they can be accessed in the HTML
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
