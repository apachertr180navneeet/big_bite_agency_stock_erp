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
    <form role="form" action="{{ route('company.payment.book.store') }}" method="post" id="receipt_voucher" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Payment Voucher details form -->
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <label for="payment" class="form-label">Payment Voucher No.</label>
                                    <input type="text" class="form-control @error('payment') is-invalid @enderror" id="payment" name="payment" value="{{ old('payment',$finalInvoiceNumber) }}" readonly>
                                    @error('payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-end">
                                    <label for="date" class="form-label">Date</label>
                                    <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date" value="{{ old('date') }}">
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="vendor" class="form-label">Payment From:</label>
                                    <select class="form-select @error('vendor') is-invalid @enderror" id="vendor" name="vendor">
                                        <option value="">Select</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                 data-purchasebooks-amount="{{ $purchasebooks->where('vendor_id', $vendor->id)->sum('grand_total') }}"
                                                 data-purchasebooks-given="{{ $paymentAmounts->where('vendor_id', $vendor->id)->sum('amount') }}"
                                                 {{ old('vendor') == $vendor->id ? 'selected' : '' }}>
                                                 {{ $vendor->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <label for="remark" class="form-label">Remarks</label>
                                    <textarea class="form-control @error('remark') is-invalid @enderror" id="remark" name="remark" rows="2">{{ old('remark') }}</textarea>
                                    @error('remark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="amount" class="form-label">Amount</label> <span id="paymentStatus" style="color: green; display: none;">Vendor has paid the full amount.</span>
                                    <input type="text" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', 0) }}">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="text" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{ old('discount', 0) }}">
                                    @error('discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="round_off" class="form-label">Round off</label>
                                    <input type="text" class="form-control @error('round_off') is-invalid @enderror" id="round_off" name="round_off" value="{{ old('round_off', 0) }}">
                                    @error('round_off')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="grand_total" class="form-label">Net Total</label>
                                    <input type="text" class="form-control" id="grand_total" name="grand_total" value="" readonly>
                                </td>
                            </tr>
                        </table>
                        <!-- Payment method options -->
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cash" name="payment_method" value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cash">Cash</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cheque" name="payment_method" value="cheque" {{ old('payment_method') == 'cheque' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cheque">Cheque</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="online_bank" name="payment_method" value="online bank" {{ old('payment_method') == 'online bank' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="online_bank">Online Bank</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="other" name="payment_method" value="other" {{ old('payment_method') == 'other' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                    @error('payment_method')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="d-none">
                                    <label for="bank" class="form-label">Bank:</label>
                                    <select class="form-select @error('bank') is-invalid @enderror" id="bank" name="bank">
                                        <option value="">Select</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}"{{ old('bank') == $bank->id ? 'selected' : '' }}>
                                                 {{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <label for="signature" class="form-label">Signature:</label>
                                    <input type="text" class="form-control @error('signature') is-invalid @enderror" id="signature" name="signature" value="{{ old('signature') }}">
                                    @error('signature')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        </table>
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
    });

    document.getElementById('vendor').addEventListener('change', function () {
        const selectedVendor = this.options[this.selectedIndex];
        const grandTotalAmount = selectedVendor.getAttribute('data-purchasebooks-amount') || 0;
        const givenAmount = selectedVendor.getAttribute('data-purchasebooks-given') || 0;
        const purchasebookAmount = grandTotalAmount - givenAmount;

        // Update the amount input with the corresponding value
        document.getElementById('amount').value = purchasebookAmount;
        document.getElementById('grand_total').value = purchasebookAmount;

        const paymentStatus = document.getElementById('paymentStatus');
        if (purchasebookAmount===0) {
            paymentStatus.style.display = 'inline';
            paymentStatus.textContent =  '(Vendor has paid the full amount.)';
        }else{
            paymentStatus.style.display = 'none';
        }

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
