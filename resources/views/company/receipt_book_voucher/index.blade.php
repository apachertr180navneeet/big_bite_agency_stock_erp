@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Receipt voucher</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('company.receipt.book.voucher.add') }}" class="btn btn-primary">
                Add Receipt voucher
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
                                    <th>Receipt number</th>
                                    <th>Customer name</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
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
        const editbaseUrl = "{{ route('company.receipt.book.voucher.edit', ['id' => ':id']) }}";
        const printbaseUrl = "{{ route('company.receipt.book.voucher.print', ['id' => ':id']) }}";

        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.receipt.book.voucher.getall') }}",
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
                { data: "receipt_vouchers_number" },
                { data: "customer_name" },
                { data: "grand_total" },
                { data: "payment_type" },
                {
                    data: "id",
                    render: function (data, type, row) {
                        const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deletePurchase(${data})">Delete</button>`;
                        const editButton = `<a href="${editbaseUrl.replace(':id', data)}" class="btn btn-sm btn-warning">Edit</a>`;
                        const printButton = `<a href="${printbaseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Print</a>`;
                        return `${deleteButton} ${editButton} ${printButton}`;
                    },
                },
            ],
        });

        // Delete
        window.deletePurchase = function(purchaseBookId) {
            console.log("Delete function triggered for ID: ", purchaseBookId);
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Receipt Book voucher and update the stock?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("company.receipt.book.voucher.destroy", ":purchaseBookId") }}'.replace(":purchaseBookId", purchaseBookId);
                    console.log("URL for delete: ", url);
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log("AJAX Success Response: ", response);
                            if (response.success) {
                                setFlash("success", "Receipt Book voucher deleted successfully and stock updated.");
                            } else {
                                setFlash("error", "There was an issue deleting the Receipt Book voucher. Please contact your system administrator.");
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
