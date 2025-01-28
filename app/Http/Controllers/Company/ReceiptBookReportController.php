<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, ReceiptBookVoucher,Company};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class ReceiptBookReportController extends Controller
{
    /**
     * Display the purchase book index page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $compId = $user->company_id;

        $companyDetail = Company::find($compId);

        // Retrieve start and end dates from the request, default to current date if not provided
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Convert to Carbon instances if you need to use them later
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Simply returning the view for purchase book index page
        return view('company.receipt_book_report.index',[
            'companyDetail' => $companyDetail,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Fetch all purchase books for the authenticated user's company and return them as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getall(Request $request)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        // Fetch all purchase books for the user's company, including vendor details
        $ReceiptBookVoucher = ReceiptBookVoucher::join('users', 'receipt_book_vouchers.customer_id', '=', 'users.id')
            ->where('receipt_book_vouchers.company_id', $compId)
            ->select('receipt_book_vouchers.*', 'users.full_name as customer_name')
            ->orderByDesc('receipt_book_vouchers.id');

          // If a date filter is provided, apply the date filter to the query
          if ($request->start_date && $request->end_date) {
            $ReceiptBookVoucher->whereBetween('receipt_book_vouchers.date', [$request->start_date, $request->end_date]);
        }
        // Fetch the filtered data
        $ReceiptBookVoucher = $ReceiptBookVoucher->get();

        // Return the purchase books data as JSON response
        return response()->json(['data' => $ReceiptBookVoucher]);
    }



    public function print($id)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        $receiptBook = ReceiptBookVoucher::join('users', 'receipt_book_vouchers.customer_id', '=', 'users.id')
        ->select('receipt_book_vouchers.*', 'users.full_name as customer_name')
        ->find($id);

        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        return view('company.receipt_book_report.print', compact('receiptBook', 'customers'));
    }
}
