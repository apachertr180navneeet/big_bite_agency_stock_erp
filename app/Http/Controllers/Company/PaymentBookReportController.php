<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, PaymentBook, Company};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class PaymentBookReportController extends Controller
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
        return view('company.payment_book_report.index',[
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
        $ReceiptBookVoucher = PaymentBook::join('users', 'payment_books.vendor_id', '=', 'users.id')
            ->where('payment_books.company_id', $compId)
            ->select('payment_books.*', 'users.full_name as vendor_name')
            ->orderByDesc('payment_books.id');

              // If a date filter is provided, apply the date filter to the query
              if ($request->start_date && $request->end_date) {
                $ReceiptBookVoucher->whereBetween('payment_books.date', [$request->start_date, $request->end_date]);
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

        $paymentBook = PaymentBook::join('users', 'payment_books.vendor_id', '=', 'users.id')
        ->select('payment_books.*', 'users.full_name as vendor_name')
        ->find($id);

        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        return view('company.payment_book_report.print', compact('paymentBook', 'customers'));
    }
}
