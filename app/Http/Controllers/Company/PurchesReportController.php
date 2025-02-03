<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, PurchesBook, PurchesBookItem, Company,Bank,SubCompany};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class PurchesReportController extends Controller
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
        return view('company.purches_report.index',[
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
        $purchesBooks = PurchesBook::join('users', 'purches_books.vendor_id', '=', 'users.id')
            ->where('purches_books.company_id', $compId)
            ->select('purches_books.*', 'users.full_name as vendor_name')
            ->orderByDesc('purches_books.id');

            // If a date filter is provided, apply the date filter to the query
            if ($request->start_date && $request->end_date) {
                $purchesBooks->whereBetween('purches_books.date', [$request->start_date, $request->end_date]);
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

        $purchaseReport = PurchesBook::with('purchesbookitem.item.variation', 'purchesbookitem.item.tax')
            ->join('users', 'purches_books.vendor_id', '=', 'users.id')
            ->select('purches_books.*', 'users.full_name as vendor_name', 'users.address as vendor_address', 'users.city as vendor_city', 'users.state as vendor_state', 'users.gst_no as vendor_gst_no', 'users.phone as vendor_phone')
            ->find($id);

        $subCompany = SubCompany::find($purchaseReport->sub_compnay_id);

        $bank = Bank::where('company_id', $compId)->where('show_invoice', '1')->first();

        $grand_total = $purchaseReport->grand_total;
        $grandtotalwrod = $this->convertNumberToWords($grand_total); // Use $this-> for method call

        return view('company.purches_report.print', compact('purchaseReport', 'grandtotalwrod', 'bank','subCompany'));
    }

    public function convertNumberToWords($num) {
        $words = array(
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 
            50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 
            90 => 'Ninety'
        );
    
        // Handle numbers less than 20
        if ($num < 20) {
            return $words[$num];
        }
        // Handle numbers less than 100
        elseif ($num < 100) {
            return $words[intval($num / 10) * 10] . ' ' . ($num % 10 ? $words[$num % 10] : '');
        }
        // Handle numbers less than 1000
        elseif ($num < 1000) {
            return $words[intval($num / 100)] . ' Hundred' . ($num % 100 ? ' and ' . $this->convertNumberToWords($num % 100) : '');
        }
        // Handle numbers less than 10000 (Thousand)
        elseif ($num < 100000) {
            return $this->convertNumberToWords(intval($num / 1000)) . ' Thousand' . ($num % 1000 ? ' ' . $this->convertNumberToWords($num % 1000) : '');
        }
        // Handle numbers less than 1000000 (Lakh)
        elseif ($num < 10000000) {
            return $this->convertNumberToWords(intval($num / 100000)) . ' Lakh' . ($num % 100000 ? ' ' . $this->convertNumberToWords($num % 100000) : '');
        }
        // Handle numbers less than 100000000 (Crore)
        elseif ($num < 100000000) {
            return $this->convertNumberToWords(intval($num / 10000000)) . ' Crore' . ($num % 10000000 ? ' ' . $this->convertNumberToWords($num % 10000000) : '');
        }
        // Handle numbers exactly 100 Crore
        elseif ($num == 100000000) {
            return 'One Hundred Crore';
        }
    
        return $num; // for larger numbers
    }    
}
