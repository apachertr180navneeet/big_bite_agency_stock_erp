@extends('company.layouts.app') @section('style')
<!-- Add any necessary styles here -->
<style>
    .table-borderless td,
    .table-borderless th {
        border: none;
    }
</style>
@endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Payment Voucher</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button id="print" class="btn btn-primary">
                Print
            </button>
        </div>
    </div>
    <div class="row" id="printdata">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="border p-3">
                            <div class="row mb-3">
                                <div class="col-6"><strong>Payment Voucher No.:</strong> {{ $paymentBook->receipt_vouchers_number }}</div>
                                <div class="col-6 text-end"><strong>Date:</strong> {{ \Carbon\Carbon::parse($paymentBook->date)->format('d-m-Y') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12"><strong>Payment From:</strong> {{ $paymentBook->vendor_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-8">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><strong>Amount</strong></td>
                                                <td>{{ $paymentBook->amount }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Discount</strong></td>
                                                <td>{{ $paymentBook->discount }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Round off</strong></td>
                                                <td>{{ $paymentBook->round_off }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Net Total</strong></td>
                                                <td><strong>{{ $paymentBook->grand_total }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="remarks"><strong>Remarks</strong></label>
                                        <textarea id="remarks" class="form-control" rows="5">{{ $paymentBook->remark }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <strong>Payment Method:</strong><br />
                                        <p>{{ $paymentBook->payment_type }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <strong>Signature:</strong>
                                </div>
                            </div>
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
        $(document).ready(function(){
            $('#print').on('click', function() {
                var printContents = $('#printdata').html();
                $('body').html(printContents);
                window.print();
                location.reload();
            });
        });
    </script>
@endsection
