@extends('pagar_book.layouts.app')

@section('style')
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">

    <div class="row">
        <div class="col-md-6 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Employee Ledger Report</span>
            </h5>
        </div>
    </div>

    {{-- Salary Table --}}
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header fw-bold">Salaries Paid</div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="SalaryTable">
                            <thead>
                                <tr>
                                    <th>Month/Year</th>
                                    <th>Pay Date</th>
                                    <th>Deduction (from Advance)</th>
                                    <th>Salary Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empSalarys as $empSalary)
                                    <tr>
                                        <td>{{ $empSalary->slarly_mounth }}</td>
                                        <td>{{ $empSalary->pay_date }}</td>
                                        <td>{{ number_format($empSalary->diduction_amountfromadvance, 2) }}</td>
                                        <td>{{ number_format($empSalary->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Advance Table --}}
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header fw-bold">Advances Taken</div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" id="AdvanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Advance Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $advance)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($advance->created_at)->format('d-m-Y') }}</td>
                                        <td>{{ number_format($advance->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Section --}}
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header fw-bold">Summary</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Salary Paid</span>
                            <span><strong>₹{{ number_format($totalSalaryPaid, 2) }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Advance Taken</span>
                            <span><strong>₹{{ number_format($totalAdvance, 2) }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Deduction from Advance</span>
                            <span><strong>₹{{ number_format($totalDeduction, 2) }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <span>Remaining Advance</span>
                            <span><strong>₹{{ number_format($remainingAdvance, 2) }}</strong></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
@endsection
