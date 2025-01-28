@extends('company.layouts.app')

@section('style')
<!-- Add any necessary styles here -->
<style>
    .table-borderless td, .table-borderless th {
        border: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Payment Voucher</span>
            </h5>
        </div>
    </div>
    <form role="form" action="{{ route('company.payment.book.update', $paymentBook->id) }}" method="post" id="receipt_voucher" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Receipt Voucher details form -->
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <label for="payment" class="form-label">Payment Voucher No.</label>
                                    <input type="text" class="form-control" id="payment" name="payment" value="{{ $paymentBook->payment_vouchers_number }}">
                                </td>
                                <td class="text-end">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control" type="date" id="date" name="date" value="{{ $paymentBook->date }}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="vendor" class="form-label">Payment From:</label>
                                    <select class="form-select" id="vendor" name="vendor">
                                        <option>Select</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ $vendor->id == $paymentBook->vendor_id ? 'selected' : '' }}>
                                                {{ $vendor->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <label for="remark" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remark" name="remark" rows="2">{{ $paymentBook->remark }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="text" class="form-control" id="amount" value="{{ $paymentBook->amount }}" name="amount">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="discount" name="discount" value="{{ $paymentBook->discount }}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="round_off" class="form-label">Round off</label>
                                    <input type="text" class="form-control" id="round_off" name="round_off" value="{{ $paymentBook->round_off }}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="grand_total" class="form-label">Net Total</label>
                                    <input type="text" class="form-control" id="grand_total" name="grand_total" value="{{ $paymentBook->grand_total }}" readonly>
                                </td>
                            </tr>
                        </table>
                        <!-- Payment method options -->
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cash" name="payment_method" value="cash" {{ $paymentBook->payment_type == 'cash' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cash">Cash</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cheque" name="payment_method" value="cheque" {{ $paymentBook->payment_type == 'cheque' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cheque">Cheque</label>payment_type
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="online_bank" name="payment_method" value="online bank" {{ $paymentBook->payment_type == 'online bank' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="online_bank">Online Bank</label>payment_type
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="other" name="payment_method" value="other" {{ $paymentBook->payment_type == 'other' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </td>
                                <td class="{{ $paymentBook->payment_type == 'online bank' ? '' : 'd-none' }}">
                                    <label for="bank" class="form-label">Bank:</label>
                                    <select class="form-select @error('bank') is-invalid @enderror" id="bank" name="bank">
                                        <option value="">Select</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ $paymentBook->bank_id == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <label for="signature" class="form-label">Signature:</label>
                                    <input type="text" class="form-control" id="signature" name="signature" value="{{ $paymentBook->signature }}">
                                </td>
                            </tr>
                        </table>
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')

<script>
    $(document).ready(function() {
        function calculateGrandTotal() {
            var amount = parseFloat($('#amount').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;
            var round_off = parseFloat($('#round_off').val()) || 0;

            var grand_total = amount - discount + round_off;

            $('#grand_total').val(grand_total.toFixed(2));
        }

        $('#amount, #discount, #round_off').on('keyup', function() {
            calculateGrandTotal();
        });

        // Trigger the calculation on page load
        calculateGrandTotal();
    });

    document.getElementById('date').addEventListener('change', function() {
        let dateValue = this.value; // Format will be YYYY-MM-DD
        let dateParts = dateValue.split('-');
        if (dateParts.length === 3) {
            // Reformat to DD/MM/YYYY
            let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
        }
    });

    $(document).ready(function () {
        $('input[name="payment_method"]').on('change', function () {
            if ($(this).val() === 'online bank') {
                $('#bank').closest('td').removeClass('d-none');
            } else {
                $('#bank').closest('td').addClass('d-none');
            }
        });

        // Trigger change event on page load to handle default selection
        $('input[name="payment_method"]:checked').trigger('change');
    });
</script>

@endsection
