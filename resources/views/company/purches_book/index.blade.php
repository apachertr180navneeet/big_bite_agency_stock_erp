@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Purchase Book</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('company.purches.book.add') }}" class="btn btn-primary">
                Add Purchase Invoice
            </a>
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
                                    <th>Date</th>
                                    <th>Invoice number</th>
                                    <th>Vendor</th>
                                    <th>Sub Company</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
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

@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Base URL for the edit route
        const baseUrl = "{{ route('company.purches.book.edit', ['id' => ':id']) }}";
        const baseviewUrl = "{{ route('company.purches.book.view', ['id' => ':id']) }}";
        const printbaseUrl = "{{ route('company.purches.report.print', ['id' => ':id']) }}";
        const pReturnbaseUrl = "{{ route('company.purches.book.preturn', ['id' => ':id']) }}";

        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.purches.book.getall') }}",
                type: 'GET',
            },
            order: [[0, 'desc']],  // Order by the first column (date) in descending order
            columns: [
                {
                    data: "date",
                    render: function (data, type, row) {
                        // Using moment.js to format the date
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                { data: "invoice_number" },
                { data: "vendor_name" },
                { data: "sub_company_name" },
                { data: "payment_type" },
                { data: "grand_total" },
                {
                    data: "id",
                    render: function (data, type, row) {
                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deletePurchase(${data})">Delete</button>`;
                        //const editButton = `<a href="${baseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Edit</a>`;
                        const viewButton = `<a href="${baseviewUrl.replace(':id', data)}" class="btn btn-sm btn-info">View</a>`;
                        const printButton = `<a href="${printbaseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Print</a>`;
                        const pReturnButton = `<a href="${pReturnbaseUrl.replace(':id', data)}" class="btn btn-sm btn-success">P-Return</a>`;
                        return `${deleteButton} ${viewButton} ${printButton} ${pReturnButton}`;
                    },
                },
            ],
        });

        // Delete
        window.deletePurchase = function(purchaseBookId) {
            console.log("Delete function triggered for ID: ", purchaseBookId);
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Purchase Book and update the stock?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("company.purches.book.destroy", ":purchaseBookId") }}'.replace(":purchaseBookId", purchaseBookId);
                    console.log("URL for delete: ", url);
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log("AJAX Success Response: ", response);
                            if (response.success) {
                                setFlash("success", "Purchase book deleted successfully and stock updated.");
                            } else {
                                setFlash("error", "There was an issue deleting the purchase book. Please contact your system administrator.");
                            }
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            console.log("AJAX Error: ", error);
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
