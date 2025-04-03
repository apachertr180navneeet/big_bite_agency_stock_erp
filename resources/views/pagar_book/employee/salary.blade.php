@extends('pagar_book.layouts.app') @section('style') @endsection @section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Salary</span>
            </h5>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Create salary
            </button>
        </div>
    </div>
    <div class="row">
        <h5>Total Advance Amount :- {{ $totalFinalAdvanceAmount }}</h5>
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="ItemTable">
                            <thead>
                                <tr>
                                    <th>Month/Year</th>
                                    <th>Deduction</th>
                                    <th>Amount</th>
                                    {{--  <th>Action</th>  --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empSalarys as $empSalary) 
                                    <tr>
                                        <td>{{ $empSalary->slarly_mounth }}</td>
                                        <td>{{ $empSalary->diduction_amount }}</td>
                                        <td>{{ $empSalary->amount }}</td>
                                        {{--  <td></td>  --}}
                                    </tr>
                                @endforeach
                            </tbody>
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
                <h5 class="modal-title" id="exampleModalLabel1">Create Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $userId }}">
                    <input type="hidden" name="slarly_mounth" id="slarly_mounth" value="{{ $currentMonth }}">
                    <div class="col-md-12 mb-3">
                        <label for="total_working_day" class="form-label">Total Working Days</label>
                        <input type="text" id="total_working_day" class="form-control" value="{{ $totalWorkingDays }}" placeholder="Enter Total Working Days" readonly />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="total_present_day" class="form-label">Total Present Days</label>
                        <input type="text" id="total_present_day" class="form-control" value="" placeholder="Enter Total Present Days"/>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="addvanceAmount" class="form-label">Total Advance Amount</label>
                        <input type="text" id="addvanceAmount" class="form-control" value="{{ $totalFinalAdvanceAmount }}" placeholder="Enter Advance Amount" readonly />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="diduction_amountfromadvance" class="form-label">Diduction Amount from Advance</label>
                        <input type="text" id="diduction_amountfromadvance" class="form-control" value="0" placeholder="Enter Diduction Amount from Advance" />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="diduction_amount" class="form-label">Diduction Amount</label>
                        <input type="text" id="diduction_amount" class="form-control" value="" placeholder="Enter Diduction Amount" readonly />
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="base_salary" class="form-label">Base Salary</label>
                        <input type="text" id="base_salary" class="form-control" value="{{ $base_salary }}" placeholder="Enter Base Salary" readonly/>
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="amount" class="form-label">Salary Amount</label>
                        <input type="text" id="amount" class="form-control" value="" placeholder="Enter Salary Amount" readonly/>
                        <small class="error-text text-danger"></small>
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


@endsection @section('script')
<script>
    $(document).ready(function () {
        
        const table = $("#ItemTable").DataTable({
            processing: true,
        });

        // Handle form submission via AJAX
        $('#AddItem').click(function(e) {
            e.preventDefault();

            // Collect form data
            let data = {
                user_id: $('#user_id').val(),
                slarly_mounth: $('#slarly_mounth').val(),
                total_working_day: $('#total_working_day').val(),
                total_present_day: $('#total_present_day').val(),
                diduction_amount: $('#diduction_amount').val(),
                diduction_amountfromadvance: $('#diduction_amountfromadvance').val(),
                amount: $('#amount').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
            };


            // Clear previous validation error messages
            $('.error-text').text('');

            $.ajax({
                url: '{{ route('pagar.book.employee.salarystore') }}', // Adjust the route as necessary
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                       
                        location.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    location.reload();
                }
            });
        });

        // Flash message function using Toast.fire
        function setFlash(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }
    });
</script>

<script>
    $(document).ready(function () {
        function calculateSalary() {
            let totalWorkingDays = parseFloat($("#total_working_day").val()) || 0;
            let totalPresentDays = parseFloat($("#total_present_day").val()) || 0;
            let baseSalary = parseFloat($("#base_salary").val()) || 0;
            let advanceDeduction = parseFloat($("#diduction_amountfromadvance").val()) || 0;
    
            if (totalWorkingDays > 0 || baseSalary > 0) {
                let perDaySalary = baseSalary / totalWorkingDays;
                let salary = perDaySalary * totalPresentDays;
    
                // Calculate deduction
                let deductionAmount = baseSalary - salary;
                let finalDeduction = deductionAmount + advanceDeduction; // Include advance deduction
                let finalSalary = baseSalary - finalDeduction; // Subtract advance deduction from salary
    
                $("#diduction_amount").val(finalDeduction.toFixed(2));
                $("#amount").val(finalSalary.toFixed(2));
            } else {
                $("#diduction_amount").val("");
                $("#amount").val("");
            }
        }
    
        // Trigger calculation on keyup for present days and advance deduction
        $("#total_present_day, #diduction_amountfromadvance").on("keyup", function () {
            calculateSalary();
        });
    });            
</script>
@endsection
