@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Sales Invoice</span>
            </h5>
        </div>
        {{--  <div class="col-md-6 text-end">
            <a href="{{ route('company.sales.book.add') }}" class="btn btn-primary">
                Add Sales Invoice
            </a>
        </div>  --}}
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
                                    <th>Dispatch number</th>
                                    <th>Sub Company</th>
                                    <th>Amount</th>
                                    <th>Customer</th>
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
        const baseUrl = "{{ route('company.sales.book.edit', ['id' => ':id']) }}";
        const viewbaseUrl = "{{ route('company.sales.book.view', ['id' => ':id']) }}";
        const printBaseUrl = "{{ route('company.sales.report.print', ['id' => ':id']) }}";
        const sReturnbaseUrl = "{{ route('company.sales.book.sreturn', ['id' => ':id']) }}";

        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.sales.book.getall.return') }}",
                type: 'GET',
            },
            order: [[0, 'desc']],  // Order by the first column (date) in descending order
            columns: [
                {
                    data: "date",
                },
                { data: "dispatch_number" },
                { data: "sub_company_name" },
                { data: "grand_total" },
                { data: "customer_name" },
                {
                    data: "id",
                    render: function (data, type, row) {
                        //const deleteButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteSales(${data})">Delete</button>`;
                        //const editButton = `<a href="${baseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Edit</a>`;
                        //const viewButton = `<a href="${viewbaseUrl.replace(':id', data)}" class="btn btn-sm btn-info">View</a>`;
                        const printButton = `<a href="${printBaseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Print</a>`;
                        //const sReturnButton = `<a href="${sReturnbaseUrl.replace(':id', data)}" class="btn btn-sm btn-success">S-Return</a>`;
                        return `${printButton}`;
                    },
                },
            ],
        });

        // Delete
        window.deleteSales = function(SalesBookId) {
            console.log("Delete function triggered for ID: ", SalesBookId);
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this Sales Book and update the stock?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = '{{ route("company.sales.book.destroy", ":SalesBookId") }}'.replace(":SalesBookId", SalesBookId);
                    console.log("URL for delete: ", url);
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (response) {
                            console.log("AJAX Success Response: ", response);
                            if (response.success) {
                                setFlash("success", "Sales book deleted successfully and stock updated.");
                            } else {
                                setFlash("error", "There was an issue deleting the Sales book. Please contact your system administrator.");
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
