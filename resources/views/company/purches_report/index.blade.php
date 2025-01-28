@extends('company.layouts.app')
@section('style')

@endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Purchase Report</span>
            </h5>
        </div>
    </div>
    <style>


    </style>


    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Date Range Filters -->
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button id="filterBtn" class="btn btn-primary mt-4">Filter</button>
                            <button id="printdiv" class="btn btn-primary mt-4"> Print Data</button>
                        </div>
                    </div>
                    <div id="printThis">
                        <!-- Header for Print with Dynamic Data -->
                        <div id="printHeader" class="text-center mb-4">
                            <h4 id="companyName"></h4>
                            <h5 id="gstNumber"></h5>
                            <p id="dateRange"></p>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" id="variationTable" style="width: 99%">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 20%;">Date</th>
                                        <th scope="col" style="width: 20%;">Invoice</th>
                                        <th scope="col" style="width: 20%;">Vendor</th>
                                        <th scope="col" style="width: 20%;">Amount</th>
                                        <th scope="col" style="width: 20%;">Action</th>
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
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        const baseUrl = "{{ route('company.purches.report.print', ['id' => ':id']) }}";

        function getSearchParam(param){
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const startDate = getSearchParam('start_date');
        const endDate = getSearchParam('end_date');

        if (startDate) {
            $('#start_date').val(startDate);
        }
        if (endDate) {
            $('#end_date').val(endDate);
        }

        const table = $('#variationTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('company.purches.report.getall') }}",
                type: 'GET',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
            },
            order: [[0, 'desc']],
            columns: [
                {
                    data: "date",
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                { data: "invoice_number" },
                { data: "vendor_name" },
                { data: "grand_total" },
                {
                    data: "id",
                    render: function (data) {
                        return `<a href="${baseUrl.replace(':id', data)}" class="btn btn-sm btn-info">Print</a>`;
                    },
                },
            ],
        });

        if (startDate || endDate) {
            table.ajax.reload();
        }

        $('#filterBtn').click(function() {
            table.ajax.reload();
        });

        $('#printdiv').click(function() {
            $('#companyName').text("{{ $companyDetail->name }}");
            $('#gstNumber').text("GSTIN: {{ $companyDetail->gstin }}");
            $('#dateRange').text("Date Range: {{ $startDate }} - {{ $endDate }}");

            var printContents = $('#printThis').html();
            var originalContents = $('body').html();

            $('body').html(printContents);
            window.print();
            $('body').html(originalContents);

            window.location.reload();
        });
    });
</script>
@endsection
