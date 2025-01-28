<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Company, Tax, Item, SalesBook, SalesBookItem,};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Carbon\Carbon; // Add this line to import Carbon

class GstReportController extends Controller
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

        $users = User::where('role', 'customer')->where('company_id', $compId)->get();

        list($usersWithGst, $usersWithoutGst) = $users->partition(function ($user) {
            return !empty($user->gst_no);
        });

        // Get only the IDs
        $usersWithGstIds = $usersWithGst->pluck('id')->toArray();
        $usersWithoutGstIds = $usersWithoutGst->pluck('id')->toArray();

         // Retrieve start and end dates from the request, default to current date if not provided
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Convert to Carbon instances if you need to use them later
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Function to calculate totals
        $calculateTotals = function($userIds) use ($startDate, $endDate) {
            $totals = [
                'invoiceCount' => 0,
                'salesBookItemInvoiceCount' => 0,
                'igst' => 0,
                'sgst' => 0,
                'cgst' => 0,
                'amountBeforeTax' => 0,
                'grandTotal' => 0,
                'itemDetails' => [],
                'totalPrice' => 0,
                'totalTaxValue' => 0,
                'totalCreditNote' => 0,
                'totalTaxableAmount' => 0,
            ];

            foreach ($userIds as $userId) {
                // Modify query to filter by date range if start and end dates are provided
                $query = SalesBook::where('customer_id', $userId);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $salesBooks = $query->get();
                $totals['invoiceCount'] += $salesBooks->count();

                foreach ($salesBooks as $sale) {
                    $totals['igst'] += floatval($sale->igst ?? 0);
                    $totals['sgst'] += floatval($sale->sgst ?? 0);
                    $totals['cgst'] += floatval($sale->cgst ?? 0);
                    $totals['amountBeforeTax'] += floatval($sale->amount_before_tax ?? 0);
                    $totals['grandTotal'] += floatval($sale->grand_total ?? 0);

                    $totals['totalCreditNote'] += floatval($sale->credit_note_amount ?? 0);
                    $totals['totalTaxableAmount'] += floatval($sale->taxable_amount ?? 0);

                    // Fetch associated SalesBookItems
                    $salesBookItems = SalesBookItem::where('sales_book_id', $sale->id)->with('item.variation', 'item.tax')->get();
                    foreach ($salesBookItems as $item) {
                        $totals['salesBookItemInvoiceCount'] += 1;

                        $itemPrice = floatval($item->rate) * floatval($item->sreturn);
                        $itemTaxValue = $itemPrice * (floatval($item->item->tax_rate ?? 0) / 100);

                        $totals['totalPrice'] += $itemPrice;
                        $totals['totalTaxValue'] += $itemTaxValue;

                        $totals['itemDetails'][] = [
                            'item_id' => $item->id,
                            'item_name' => $item->item_name,
                            'sreturn' => $item->sreturn,
                            'rate' => $item->rate,
                            'price' => $itemPrice,
                            'tax_value' => $itemTaxValue
                        ];
                    }
                }
            }

            return $totals;
        };

        // Calculate totals for B2B (users with GST) and B2C (users without GST)
        $b2bTotals = $calculateTotals($usersWithGstIds);
        $b2cTotals = $calculateTotals($usersWithoutGstIds);


        // Pass the totals to the view along with other data
        return view('company.gst_report.index', [
            'companyDetail' => $companyDetail,
            'b2btotalInvoiceCount' => $b2bTotals['invoiceCount'],
            'b2bSalesBookItemInvoiceCount' => $b2bTotals['salesBookItemInvoiceCount'],
            'b2btotalIgst' => $b2bTotals['igst'],
            'b2btotalSgst' => $b2bTotals['sgst'],
            'b2btotalCgst' => $b2bTotals['cgst'],
            'b2btotalAmountBeforeTax' => $b2bTotals['amountBeforeTax'],
            'b2btotalGrandTotal' => $b2bTotals['grandTotal'],
            'b2bItemDetails' => $b2bTotals['itemDetails'],
            'b2bTotalPrice' => $b2bTotals['totalPrice'],
            'b2bTotalTaxValue' => $b2bTotals['totalTaxValue'],
            'b2bTotalCreditNote' => $b2bTotals['totalCreditNote'],
            'b2bTotalTaxableAmount' => $b2bTotals['totalTaxableAmount'],
            'b2ctotalInvoiceCount' => $b2cTotals['invoiceCount'],
            'b2cSalesBookItemInvoiceCount' => $b2cTotals['salesBookItemInvoiceCount'],
            'b2ctotalIgst' => $b2cTotals['igst'],
            'b2ctotalSgst' => $b2cTotals['sgst'],
            'b2ctotalCgst' => $b2cTotals['cgst'],
            'b2ctotalAmountBeforeTax' => $b2cTotals['amountBeforeTax'],
            'b2ctotalGrandTotal' => $b2cTotals['grandTotal'],
            'b2cItemDetails' => $b2cTotals['itemDetails'],
            'b2cTotalPrice' => $b2cTotals['totalPrice'],
            'b2cTotalTaxValue' => $b2cTotals['totalTaxValue'],
            'b2cTotalCreditNote' => $b2cTotals['totalCreditNote'],
            'b2cTotalTaxableAmount' => $b2cTotals['totalTaxableAmount'],
            'usersWithGstIds' => $usersWithGstIds,
            'usersWithoutGstIds' => $usersWithoutGstIds,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }


    /**
    * Display the purchase book index page.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\View\View
    */
    public function filter(Request $request)
    {
        $user = Auth::user();
        $compId = $user->company_id;
        $companyDetail = Company::find($compId);

        // Retrieve start and end dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Convert to Carbon dates if available
        if ($startDate && $endDate) {
            try {
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();
            } catch (\Exception $e) {
                // Handle invalid date formats
                return redirect()->back()->with('error', 'Invalid date format.');
            }
        }

        $users = User::where('role', 'customer')->where('company_id', $compId)->get();

        list($usersWithGst, $usersWithoutGst) = $users->partition(function ($user) {
            return !empty($user->gst_no);
        });

        // Get only the IDs
        $usersWithGstIds = $usersWithGst->pluck('id')->toArray();
        $usersWithoutGstIds = $usersWithoutGst->pluck('id')->toArray();

        // Function to calculate totals
        $calculateTotals = function($userIds) use ($startDate, $endDate) {
            $totals = [
                'invoiceCount' => 0,
                'salesBookItemInvoiceCount' => 0,
                'igst' => 0,
                'sgst' => 0,
                'cgst' => 0,
                'amountBeforeTax' => 0,
                'grandTotal' => 0,
                'itemDetails' => [],
                'totalPrice' => 0,
                'totalTaxValue' => 0,
                'totalCreditNote' => 0,
                'totalTaxableAmount' => 0,
            ];

            foreach ($userIds as $userId) {
                // Modify query to filter by date range if start and end dates are provided
                $query = SalesBook::where('customer_id', $userId);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $salesBooks = $query->get();
                $totals['invoiceCount'] += $salesBooks->count();

                foreach ($salesBooks as $sale) {
                    $totals['igst'] += floatval($sale->igst ?? 0);
                    $totals['sgst'] += floatval($sale->sgst ?? 0);
                    $totals['cgst'] += floatval($sale->cgst ?? 0);
                    $totals['amountBeforeTax'] += floatval($sale->amount_before_tax ?? 0);
                    $totals['grandTotal'] += floatval($sale->grand_total ?? 0);

                    $totals['totalCreditNote'] += floatval($sale->credit_note_amount ?? 0);
                    $totals['totalTaxableAmount'] += floatval($sale->taxable_amount ?? 0);

                    // Fetch associated SalesBookItems
                    $salesBookItems = SalesBookItem::where('sales_book_id', $sale->id)->with('item.variation', 'item.tax')->get();
                    foreach ($salesBookItems as $item) {
                        $totals['salesBookItemInvoiceCount'] += 1;

                        $itemPrice = floatval($item->rate) * floatval($item->sreturn);
                        $itemTaxValue = $itemPrice * (floatval($item->item->tax_rate ?? 0) / 100);

                        $totals['totalPrice'] += $itemPrice;
                        $totals['totalTaxValue'] += $itemTaxValue;

                        $totals['itemDetails'][] = [
                            'item_id' => $item->id,
                            'item_name' => $item->item_name,
                            'sreturn' => $item->sreturn,
                            'rate' => $item->rate,
                            'price' => $itemPrice,
                            'tax_value' => $itemTaxValue
                        ];
                    }
                }
            }

            return $totals;
        };

        // Calculate totals for B2B (users with GST) and B2C (users without GST)
        $b2bTotals = $calculateTotals($usersWithGstIds);
        $b2cTotals = $calculateTotals($usersWithoutGstIds);

        // Pass the totals to the view along with other data
        return view('company.gst_report.index', [
            'companyDetail' => $companyDetail,
            'b2btotalInvoiceCount' => $b2bTotals['invoiceCount'],
            'b2bSalesBookItemInvoiceCount' => $b2bTotals['salesBookItemInvoiceCount'],
            'b2btotalIgst' => $b2bTotals['igst'],
            'b2btotalSgst' => $b2bTotals['sgst'],
            'b2btotalCgst' => $b2bTotals['cgst'],
            'b2btotalAmountBeforeTax' => $b2bTotals['amountBeforeTax'],
            'b2btotalGrandTotal' => $b2bTotals['grandTotal'],
            'b2bItemDetails' => $b2bTotals['itemDetails'],
            'b2bTotalPrice' => $b2bTotals['totalPrice'],
            'b2bTotalTaxValue' => $b2bTotals['totalTaxValue'],
            'b2bTotalCreditNote' => $b2bTotals['totalCreditNote'],
            'b2bTotalTaxableAmount' => $b2bTotals['totalTaxableAmount'],
            'b2ctotalInvoiceCount' => $b2cTotals['invoiceCount'],
            'b2cSalesBookItemInvoiceCount' => $b2cTotals['salesBookItemInvoiceCount'],
            'b2ctotalIgst' => $b2cTotals['igst'],
            'b2ctotalSgst' => $b2cTotals['sgst'],
            'b2ctotalCgst' => $b2cTotals['cgst'],
            'b2ctotalAmountBeforeTax' => $b2cTotals['amountBeforeTax'],
            'b2ctotalGrandTotal' => $b2cTotals['grandTotal'],
            'b2cItemDetails' => $b2cTotals['itemDetails'],
            'b2cTotalPrice' => $b2cTotals['totalPrice'],
            'b2cTotalTaxValue' => $b2cTotals['totalTaxValue'],
            'b2cTotalCreditNote' => $b2cTotals['totalCreditNote'],
            'b2cTotalTaxableAmount' => $b2cTotals['totalTaxableAmount'],
            'usersWithGstIds' => $usersWithGstIds,
            'usersWithoutGstIds' => $usersWithoutGstIds,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }






}
