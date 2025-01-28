<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, SalesBook, SalesBookItem, Company,Bank};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class SalesReportController extends Controller
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
        return view('company.sales_report.index',[
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
        $purchesBooks = SalesBook::join('users', 'sales_books.customer_id', '=', 'users.id')
            ->where('sales_books.company_id', $compId)
            ->select('sales_books.*', 'users.full_name as customer_name')
            ->orderByDesc('sales_books.id');

            // If a date filter is provided, apply the date filter to the query
            if ($request->start_date && $request->end_date) {
                $purchesBooks->whereBetween('sales_books.date', [$request->start_date, $request->end_date]);
            }
            // Fetch the filtered data
            $purchesBooks = $purchesBooks->get();


        // Return the purchase books data as JSON response
        return response()->json(['data' => $purchesBooks]);
    }


    public function print($id)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        $companyDetail = Company::find($compId);

        $salesReport = SalesBook::with('salesbookitem.item.variation','salesbookitem.item.tax')
            ->join('users', 'sales_books.customer_id', '=', 'users.id')
            ->select('sales_books.*', 'users.full_name as customer_name', 'users.city as customer_city' , 'users.address as customer_address', 'users.state as customer_state', 'users.gst_no as customer_gst_no', 'users.phone as customer_phone')
            ->find($id);

        $bank = Bank::where('company_id', $compId)->where('show_invoice', '1')->first();

        $grand_total = $salesReport->grand_total;
        $grandtotalwrod = $this->convertNumberToWords($grand_total);

        return view('company.sales_report.print', compact('salesReport','grandtotalwrod','bank','companyDetail'));
    }

    public function convertNumberToWords($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $dictionary = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        ];
        
        // Remove decimal part by casting to int
        $number = (int) $number;
    
        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }
    
        $string = null;
    
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = (int) ($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToWords($remainder);
                }
                break;
        }
    
        return $string;
    }    

}
