<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, BankAndCash, Bank, PaymentBook, ReceiptBookVoucher, Company};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class BankAndCashReportController extends Controller
{
    /**
     * Display the purchase book index page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        $companyDetail = Company::find($compId);

        // Retrieve start and end dates from the request, default to current date if not provided
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Convert to Carbon instances if you need to use them later
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Fetch all BankAndCash records for the user's company, including related bank details
        $bankandcashs = BankAndCash::where('bank_and_cashes.company_id', $compId)
            ->leftJoin('banks as deposite_bank', 'deposite_bank.id', '=', 'bank_and_cashes.deposite_in') // Join for deposite_in
            ->leftJoin('banks as withdraw_bank', 'withdraw_bank.id', '=', 'bank_and_cashes.withdraw_in') // Join for withdraw_in
            ->select(
                'bank_and_cashes.*',
                'deposite_bank.bank_name as deposite_bank_name',
                'withdraw_bank.bank_name as withdraw_bank_name'
            )
            ->get();

        // Simply returning the view for purchase book index page
        return view('company.contra_report.index',compact('bankandcashs','companyDetail','startDate','endDate'));
    }


    public function bankindex(Request $request)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;
        $companyDetail = Company::find($compId);

        // Retrieve start and end dates from the request, default to current date if not provided
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Convert to Carbon instances if you need to use them later
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $paymentBooks = PaymentBook::where('payment_books.company_id', $compId)
            ->where('payment_books.bank_id', '!=', '0')
            ->leftJoin('banks', 'payment_books.bank_id', '=', 'banks.id')
            ->leftJoin('users as vendors', 'payment_books.vendor_id', '=', 'vendors.id')
            ->select(
                'payment_books.id',
                'payment_books.amount',
                'payment_books.date',
                'payment_books.payment_type',
                'payment_books.company_id',
                'banks.bank_name as bank_name',
                'vendors.full_name as name', // Vendor name for payment
                DB::raw("'payment' as type") // Distinguish payment records
            );

        $receiptBookVouchers = ReceiptBookVoucher::where('receipt_book_vouchers.company_id', $compId)
            ->where('receipt_book_vouchers.bank_id', '!=', '0')
            ->leftJoin('banks', 'receipt_book_vouchers.bank_id', '=', 'banks.id')
            ->leftJoin('users as customers', 'receipt_book_vouchers.customer_id', '=', 'customers.id')
            ->select(
                'receipt_book_vouchers.id',
                'receipt_book_vouchers.amount',
                'receipt_book_vouchers.date',
                'receipt_book_vouchers.payment_type',
                'receipt_book_vouchers.company_id',
                'banks.bank_name as bank_name',
                'customers.full_name as name', // Customer name for receipt
                DB::raw("'receipt' as type") // Distinguish receipt records
            );

        $combinedRecords = $paymentBooks->union($receiptBookVouchers)->get();

        $totalReceipt = $combinedRecords->where('type', 'receipt')->sum('amount');
        $totalPayment = $combinedRecords->where('type', 'payment')->sum('amount');


        // Simply returning the view for purchase book index page
        return view('company.bank_and_cash_report.index',compact('combinedRecords','totalReceipt','totalPayment','companyDetail','startDate','endDate'));
    }

}
