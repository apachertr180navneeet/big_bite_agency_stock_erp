<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, PurchesBook, PurchesBookItem, SalesBook, SalesBookItem, StockReport,Item,Company};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class StockReportController extends Controller
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
        return view('company.stock_report.index',compact('companyDetail','startDate','endDate'));
    }

    /**
 * Fetch all purchase books for the authenticated user's company and return them as JSON.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getAll(Request $request)
{
    // Get the authenticated user and their company ID
    $user = Auth::user();
    $compId = $user->company_id;

    // Fetch all purchase books for the user's company, including vendor details
    $purchaseBooks = DB::table('items')
                    ->select(
                        'items.id',
                        'items.name',
                        'items.opening_stock',
                        DB::raw('COALESCE(purches_book_items.total_purches_book_qty, 0) as total_purches_book_qty'),
                        DB::raw('COALESCE(purches_book_items.total_preturn, 0) as total_preturn'),
                        DB::raw('COALESCE(sales_book_items.total_sales_book_qty, 0) as total_sales_book_qty'),
                        DB::raw('COALESCE(sales_book_items.total_sreturn, 0) as total_sreturn'),
                        DB::raw('COALESCE(SUM(stock_reports.quantity), 0) as total_stock_quantity'),
                        DB::raw('COALESCE(SUM(stock_reports.quantity) - COALESCE(sales_book_items.total_sreturn, 0), 0) as sale_return_stock'),
                        DB::raw('COALESCE(SUM(stock_reports.quantity) - COALESCE(purches_book_items.total_preturn, 0), 0) as purchase_return_stock')
                    )
                    ->leftJoin(DB::raw('(SELECT item_id, SUM(quantity) as total_purches_book_qty, SUM(preturn) as total_preturn FROM purches_book_items GROUP BY item_id) as purches_book_items'), 'items.id', '=', 'purches_book_items.item_id')
                    ->leftJoin(DB::raw('(SELECT item_id, SUM(quantity) as total_sales_book_qty, SUM(sreturn) as total_sreturn FROM sales_book_items GROUP BY item_id) as sales_book_items'), 'items.id', '=', 'sales_book_items.item_id')
                    ->leftJoin('stock_reports', 'items.id', '=', 'stock_reports.item_id')
                    ->where('items.company_id', $compId)
                    ->whereNull('items.deleted_at')
                    ->groupBy(
                        'items.id', 
                        'items.name', 
                        'total_purches_book_qty', 
                        'total_preturn', 
                        'total_sales_book_qty', 
                        'total_sreturn'
                    )
                    ->get();

    // Return the purchase books data as JSON response
    return response()->json(['data' => $purchaseBooks]);
}


}
