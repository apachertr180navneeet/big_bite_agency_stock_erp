@extends('admin.layouts.app') @section('style') @endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Company</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Add Comapny
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="companyTable">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Short Code</th>
                                    <th>Location</th>
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
                <h5 class="modal-title" id="exampleModalLabel1">Company Add</h5>
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
                        <label for="short_code" class="form-label">Short Code</label>
                        <input type="text" id="short_code" class="form-control" placeholder="Enter Short Code" />
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
                        <textarea class="form-control" id="address" rows="3"></textarea>
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
                        <label for="gstin" class="form-label">GSTIN</label>
                        <input type="text" id="gstin" class="form-control" placeholder="" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" class="form-select form-select">
                            <option value="">select</option>
                            <option value="type1">type1</option>
                            <option value="type2">type2</option>
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="AddComapany">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Edit Company</h5>
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
                        <label for="short_code" class="form-label">Short Code</label>
                        <input type="text" id="editshortcode" class="form-control" placeholder="Enter Short Code" />
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="editemail" class="form-control" placeholder="xxxx@xxx.xx" />
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="editphone" class="form-control" placeholder="Enter Phone" />
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="editaddress" rows="3"></textarea>
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="editstate">
                            <option selected>Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->state_name }}" data-id="{{ $state->state_id }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="editcity">
                            <option selected>Select City</option>
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="zipcode" class="form-label">Pincode</label>
                        <select class="form-select" id="editzipcode">
                            <option selected>Select Pincode</option>
                        </select>
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="gstin" class="form-label">GSTIN</label>
                        <input type="text" id="editgstin" class="form-control" placeholder="Enter GSTIN" />
                        <small class="error-text text-danger"></small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select id="edittype" class="form-select form-select">
                            <option value="">Select Type</option>
                            <option value="type1">Type 1</option>
                            <option value="type2">Type 2</option>
                        </select>
                        <small class="error-text text-danger"></small>
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
        // Initialize DataTable for companies
        const table = $("#companyTable").DataTable({
            processing: true,
            ordering: false,
            ajax: {
                url: "{{ route('admin.company.allcompany') }}", // URL to fetch data
            },
            columns: [
                {
                    data: "name",
                    render: (data, type, row) => {
                        const url = '{{ route("admin.company.show", ":userId") }}'.replace(":userId", row.id);
                        return `<a href="${url}">${row.name}</a>`;
                    },
                },
                { data: "short_code" },
                {
                    data: "city",
                    render: (data, type, row) => `<a href="#">${row.city}</a>`,
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
                        const logourl = '{{ route("admin.company.logo", ":userId") }}'.replace(":userId", row.id);
                        // Status button to toggle activation
                        const statusButton = row.status === "inactive"
                            ? `<button type="button" class="btn btn-sm btn-success" onclick="updateUserStatus(${row.id}, 'active')">Activate</button>`
                            : `<button type="button" class="btn btn-sm btn-danger" onclick="updateUserStatus(${row.id}, 'inactive')">Deactivate</button>`;

                        // Other action buttons: delete, edit, and add user
                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">Delete</button>`;
                        const editButton = `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${row.id})">Edit</button>`;
                        const viewButton = `<a href="{{ route('admin.user.index') }}?id=${row.id}" class="btn btn-sm btn-info">Add User</a>`;
                        const logoButton = `<a href="${logourl}" class="btn btn-sm btn-success">Add Logo</a>`;

                        return `${statusButton} ${deleteButton} ${editButton} ${viewButton} ${logoButton}`;
                    },
                },
            ],
        });

        // Handle form submission via AJAX for adding a company
        $('#AddComapany').click(function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Collect form data
            const data = {
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                short_code: $('#short_code').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zipcode: $('#zipcode').val(),
                type: $('#type').val(),
                gstin: $('#gstin').val(),
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            };

            // Clear previous validation error messages
            $('.error-text').text('');

            // AJAX call to submit form data
            $.ajax({
                url: '{{ route('admin.company.store') }}', // Adjust route as necessary
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        setFlash("success", response.message); // Show success message
                        $('#addModal').modal('hide'); // Close modal
                        $('#addModal').find('input, textarea, select').val(''); // Reset form fields
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        // Display validation errors
                        if (response.errors) {
                            for (let field in response.errors) {
                                $(`#${field}`).siblings('.error-text').text(response.errors[field][0]);
                            }
                        } else {
                            setFlash("error", response.message);
                        }
                    }
                },
                error: function () {
                    setFlash("error", "An unexpected error occurred.");
                },
            });
        });

        // Define editUser function to populate modal with user data for editing
        function editUser(userId) {
            const url = '{{ route("admin.company.get", ":userid") }}'.replace(":userid", userId); // Generate the correct URL

            // AJAX request to fetch company data
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    const company = data.company;
                    const cities = data.cities;
                    const pincodes = data.pincodes;

                    // Populate the modal fields with the retrieved company data
                    $('#compid').val(company.id);
                    $('#editname').val(company.name);
                    $('#editemail').val(company.email);
                    $('#editphone').val(company.phone);
                    $('#editaddress').val(company.address);
                    $('#editstate').val(company.state);
                    $('#editshortcode').val(company.short_code);
                    $('#editgstin').val(company.gstin);
                    $('#edittype').val(company.type);

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

                    // Show the modal for editing
                    $('#editModal').modal('show');
                },
                error: function () {
                    setFlash("error", "Error retrieving company data. Please try again later.");
                },
            });
        }

        // Handle form submission for editing a company
        $('#EditComapany').on('click', function () {
            const userId = $('#compid').val(); // Get user ID

            // AJAX request to update company details
            $.ajax({
                url: '{{ route('admin.company.update') }}', // Adjust the route accordingly
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: userId,
                    name: $('#editname').val(),
                    email: $('#editemail').val(),
                    phone: $('#editphone').val(),
                    address: $('#editaddress').val(),
                    city: $('#editcity').val(),
                    state: $('#editstate').val(),
                    zipcode: $('#editzipcode').val(),
                    type: $('#edittype').val(),
                    gstin: $('#editgstin').val(),
                    short_code: $('#editshortcode').val(),
                },
                success: function (response) {
                    if (response.success) {
                        setFlash("success", response.message);
                        $('#editModal').modal('hide'); // Close modal after successful edit
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        setFlash("error", response.message);
                    }
                },
                error: function () {
                    setFlash("error", "Error updating company data.");
                },
            });
        });

        // Function to update user status (active/inactive)
        function updateUserStatus(userId, status) {
            const message = status === "active" ? "Company will be able to log in after activation." : "Company will not be able to log in after deactivation.";

            Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to update status
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.company.status') }}",
                        data: { userId, status, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            setFlash("success", status === "active" ? "Company activated successfully." : "Company deactivated successfully.");
                            table.ajax.reload(); // Reload DataTable
                        },
                        error: function () {
                            setFlash("error", "Error changing company status.");
                        },
                    });
                }
            });
        }

        // Function to delete a user
        function deleteUser(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Company?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("admin.company.destroy", ":userId") }}'.replace(":userId", userId);

                    // AJAX request to delete user
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            setFlash("success", "Company deleted successfully.");
                            table.ajax.reload(); // Reload DataTable
                        },
                        error: function () {
                            setFlash("error", "Error deleting company.");
                        },
                    });
                }
            });
        }

        // Helper function to show flash messages
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
        window.logoButton = logoButton;
    });

    // Event handling for dynamic state and city selection
    $(document).ready(function () {
        // Fetch cities when state is selected in 'Add Vendor' modal
        $('#state').on('change', function () {
            const stateId = $('#state').find(':selected').attr('data-id');
            fetchCities(stateId, $('#city'));
        });

        // Fetch pincodes when city is selected in 'Add Vendor' modal
        $('#city').on('change', function () {
            const cityId = $('#city').find(':selected').attr('data-id');
            fetchPincodes(cityId, $('#zipcode'));
        });

        // Fetch cities when state is selected in 'Edit Vendor' modal
        $('#editstate').on('change', function () {
            const stateId = $('#editstate').find(':selected').attr('data-id');
            fetchCities(stateId, $('#editcity'));
        });

        // Fetch pincodes when city is selected in 'Edit Vendor' modal
        $('#editcity').on('change', function () {
            const cityId = $('#editcity').find(':selected').attr('data-id');
            fetchPincodes(cityId, $('#editzipcode'));
        });

        // Function to fetch cities based on state ID
        function fetchCities(stateId, cityElement) {
            if (stateId) {
                $.ajax({
                    url: '{{ route("ajax.getCities", "") }}/' + stateId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        cityElement.empty().append('<option selected>Select City</option>');
                        data.forEach(city => {
                            cityElement.append(`<option value="${city.city_name}" data-id="${city.id}">${city.city_name}</option>`);
                        });
                    },
                });
            }
        }

        // Function to fetch pincodes based on city ID
        function fetchPincodes(cityId, zipcodeElement) {
            if (cityId) {
                $.ajax({
                    url: '{{ route("ajax.getPincodes", "") }}/' + cityId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        zipcodeElement.empty().append('<option selected>Select Pincode</option>');
                        data.forEach(pincode => {
                            zipcodeElement.append(`<option value="${pincode.pincode}">${pincode.pincode}</option>`);
                        });
                    },
                });
            }
        }
    });

</script>
@endsection
