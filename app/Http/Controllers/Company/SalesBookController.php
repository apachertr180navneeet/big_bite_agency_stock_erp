<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Company, Tax, Item, SalesBook, SalesBookItem, StockReport, State, SubCompany};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Exception;

class SalesBookController extends Controller
{
    public function getLastDigit($str)
    {
        // Use regular expression to find all digits in the string
        preg_match_all('/\d/', $str, $matches);

        // If there are digits found, return the last one
        if (!empty($matches[0])) {
            return end($matches[0]);
        }

        // Return null or a message if no digits are found
        return null;
    }

    /**
     * Display the sales book index page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Simply returning the view for sales book index page
        return view('company.sales_book.index');
    }

    /**
     * Fetch all sales books for the authenticated user's company and return them as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getall(Request $request)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        // Fetch all sales books for the user's company, including vendor details
        $salesBooks = SalesBook::join('users', 'sales_books.customer_id', '=', 'users.id')
            ->join('sub_company', 'sales_books.sub_compnay_id', '=', 'sub_company.id')
            ->where('sales_books.company_id', $compId)
            ->select('sales_books.*', 'users.full_name as customer_name', 'sub_company.name as sub_company_name')
            ->orderByDesc('sales_books.id')
            ->get();


        // Return the sales books data as JSON response
        return response()->json(['data' => $salesBooks]);
    }

    /**
     * Show the form for adding a new sales book.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        // Fetch the company details for the authenticated user's company
        $companyDetails = Company::find($compId);
        $companyShortCode = $companyDetails->short_code;
        $companyState = $companyDetails->state;


        // Get the maximum invoice number for the company's purchases
        $latestInvoiceNumber = SalesBook::where('company_id', $compId)->max('dispatch_number');
        $lastDigit = $this->getLastDigit($latestInvoiceNumber);
        // Generate the next invoice number by incrementing the latest invoice or default to 1
        $lastDigit = (int) $lastDigit; // Convert to integer
        $nextInvoiceNumber = $lastDigit ? $lastDigit + 1 : 1;


        // Format the invoice number to have 5 digits, with leading zeros if necessary
        $formattedInvoiceNumber = sprintf('%05d', $nextInvoiceNumber);
        $finalInvoiceNumber = $companyShortCode . '-SB' . '-' . $formattedInvoiceNumber;

        // Get the current date
        $currentDate = Carbon::now()->format('d/m/Y'); // DD/MM/YYYY format

        // Get the active sub companies
        $activeSubComapny = SubCompany::where([
            ['company_id', $compId],
            ['status', 'active']
        ])->get();


        $states = State::all();

        // Pass the vendors and items data to the view for adding a new sales book
        return view('company.sales_book.add', [
            'invoiceNumber' => $finalInvoiceNumber,
            'currentDate' => $currentDate,
            'companyState' => $companyState,
            'states' => $states,
            'subComapnys' => $activeSubComapny
        ]);
    }

    public function store(Request $request)
    {
        // // Validate the request data
        // $request->validate([
        //     'date' => 'required',
        //     'dispatch' => 'required|string|max:255',
        //     'customer' => 'required|exists:users,id',
        //     'weight' => 'required',
        //     'transport' => 'required',
        //     'vehicle_no' => 'required',
        //     'other_expense' => 'required|numeric',
        //     'discount' => 'required|numeric',
        //     'round_off' => 'required|numeric',
        //     'grand_total' => 'required|numeric',
        //     'items.*' => 'required|exists:items,id',
        //     'quantities.*' => 'required|numeric|min:0',
        //     'rates.*' => 'required|numeric|min:0',
        //     'taxes.*' => 'required|numeric|min:0',
        //     'totalAmounts.*' => 'required|numeric|min:0',
        // ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Get the authenticated user and their company ID
            $user = Auth::user();
            $compId = $user->company_id;

            // Save the sales book details in the sales_books table
            $salesBook = SalesBook::create([
                'date' => $request->date,
                'company_id' => $compId,
                'dispatch_number' => $request->dispatch,
                'customer_id' => $request->customer,
                'sub_compnay_id' => $request->sub_compnay_id,
                'payment_type' => $request->payment_type,
                'item_weight' => $request->weight,
                'transport' => $request->transport,
                'vehicle_no' => $request->vehicle_no,
                'igst' => $request->igst,
                'cgst' => $request->cgst,
                'sgst' => $request->sgst,
                'amount_before_tax' => $request->amount_before_tax,
                'other_expense' => $request->other_expense,
                'discount' => $request->discount,
                'round_off' => $request->round_off,
                'grand_total' => $request->grand_total,
                'recived_amount' => $request->received_amount,
                'balance_amount' => $request->balance_amount,
            ]);

            // Save each item in the sales_book_items table
            foreach ($request->items as $index => $itemId) {
                SalesBookItem::create([
                    'sales_book_id' => $salesBook->id,
                    'item_id' => $itemId,
                    'category' => $request->categorys[$index],
                    'quantity' => $request->quantities[$index],
                    'rate' => $request->rates[$index],
                    'tax' => $request->taxes[$index],
                    'amount' => $request->totalAmounts[$index],
                ]);

                $quantity = $request->quantities[$index];
                // Update or create a StockReport entry
                $stockReport = StockReport::where('item_id', $itemId)->first();
                if ($stockReport) {
                    $stockReport->quantity -= $quantity;
                    $stockReport->save();
                } else {
                    StockReport::create([
                        'item_id' => $itemId,
                        'quantity' => $quantity,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('company.sales.book.index')->with('success', 'Sales book entry saved successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            dd($e);
            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while saving the sales book entry.');
        }
    }


    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Find the sales book
                $salesBook = SalesBook::with('salesbookitem')->find($id);

                if (!$salesBook) {
                    throw new \Exception('sales Book not found.');
                }

                // Loop through the items to update the stock
                foreach ($salesBook->salesbookitem as $item) {
                    $stockReport = StockReport::where('item_id', $item->item_id)->first();
                    if ($stockReport) {
                        $stockReport->quantity -= $item->quantity;
                        $stockReport->save();
                    }
                }

                // Delete items related to the sales book
                $salesBook->salesbookitem()->delete();

                // Delete the sales book itself
                $salesBook->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        // Fetch the company details for the authenticated user's company
        $companyDetails = Company::find($compId);
        $companyShortCode = $companyDetails->short_code;
        $companyState = $companyDetails->state;

        $salesBook = SalesBook::with('salesbookitem.item.variation', 'salesbookitem.item.tax')->find($id);


        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        // Fetch all items with their variations and tax details for the user's company
        $items = Item::join('variations', 'items.variation_id', '=', 'variations.id')
            ->join('taxes', 'items.tax_id', '=', 'taxes.id')
            ->where('items.company_id', $compId)
            ->select('items.*', 'variations.name as variation_name', 'taxes.rate as tax_rate')
            ->get();

        return view('company.sales_book.edit', compact('salesBook', 'customers', 'items', 'companyState'));
    }
    public function view($id)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        $salesBook = SalesBook::with('salesbookitem.item.variation')->find($id);

        $subCompany = SubCompany::find($salesBook->sub_compnay_id);


        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        // Fetch all items with their variations and tax details for the user's company
        $items = Item::join('variations', 'items.variation_id', '=', 'variations.id')
            ->join('taxes', 'items.tax_id', '=', 'taxes.id')
            ->where('items.company_id', $compId)
            ->select('items.*', 'variations.name as variation_name', 'taxes.rate as tax_rate')
            ->get();

        return view('company.sales_book.view', compact('salesBook', 'customers', 'items','subCompany'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'date' => 'required|date',
            'dispatch' => 'required|string',
            'customer' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'rates' => 'required|array|min:1',
            'taxes' => 'required|array|min:1',
            'totalAmounts' => 'required|array|min:1',
            'igst' => 'required|numeric|min:0',
            'cgst' => 'required|numeric|min:0',
            'sgst' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ], [
            'items.required' => 'No items provided. Please add items to the purchase book.',
            'quantities.required' => 'Quantities are required for all items.',
            'rates.required' => 'Rates are required for all items.',
            'taxes.required' => 'Taxes are required for all items.',
            'totalAmounts.required' => 'Total amounts are required for all items.',
        ]);



        $salesBook = SalesBook::with('salesbookitem')->find($id);

        // Step 1: Subtract old quantities from StockReport
        foreach ($salesBook->salesbookitem as $item) {
            $stockReport = StockReport::where('item_id', $item->item_id)->first();
            if ($stockReport) {
                $stockReport->quantity -= $item->quantity;
                $stockReport->save();
            }
        }

        // Step 2: Update Purchase Book details
        $salesBook->date = $request->date;
        $salesBook->dispatch_number = $request->dispatch;
        $salesBook->customer_id = $request->customer;
        $salesBook->item_weight = $request->weight;
        $salesBook->transport = $request->transport;
        $salesBook->vehicle_no = $request->vehicle_no;
        $salesBook->igst = $request->igst;
        $salesBook->cgst = $request->cgst;
        $salesBook->sgst = $request->sgst;
        $salesBook->other_expense = $request->other_expense;
        $salesBook->discount = $request->discount;
        $salesBook->round_off = $request->round_off;
        $salesBook->grand_total = $request->grand_total;
        $salesBook->amount_before_tax = $request->amount_before_tax;
        $salesBook->recived_amount = $request->received_amount;
        $salesBook->balance_amount = $request->balance_amount;

        // Delete existing items to reattach with updated quantities
        $salesBook->salesbookitem()->delete();

        // Step 3: Add new quantities to StockReport and attach items to PurchaseBook
        foreach ($request->items as $index => $itemId) {
            $quantity = $request->quantities[$index];
            $amount = $request->rates[$index];
            $tax = $request->taxes[$index];
            $total = $request->totalAmounts[$index];

            // Check if the item exists before updating or creating stock report entry
            if (Item::find($itemId)) {
                // Update or create a StockReport entry
                $stockReport = StockReport::where('item_id', $itemId)->first();
                if ($stockReport) {
                    $stockReport->quantity += $quantity;
                    $stockReport->save();
                } else {
                    StockReport::create([
                        'item_id' => $itemId,
                        'quantity' => $quantity,
                    ]);
                }

                // Recreate the purchase book item record
                $salesBook->salesbookitem()->create([
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'rate' => $amount,
                    'tax' => $tax,
                    'amount' => $total,
                    'sales_book_id' => $id,
                ]);
            } else {
                // Handle the case where the item does not exist
                return redirect()->back()->withInput()->withErrors(["Item with ID $itemId does not exist."]);
            }
        }

        $salesBook->save();

        return redirect()->route('company.sales.book.index')->with('success', 'Purchase book updated successfully.');
    }

    public function sreturn($id)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        $salesBook = SalesBook::with('salesbookitem.item.variation', 'salesbookitem.item.tax')->find($id);


        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        // Fetch all items with their variations and tax details for the user's company
        $items = Item::join('variations', 'items.variation_id', '=', 'variations.id')
            ->join('taxes', 'items.tax_id', '=', 'taxes.id')
            ->where('items.company_id', $compId)
            ->select('items.*', 'variations.name as variation_name', 'taxes.rate as tax_rate')
            ->get();

        return view('company.sales_book.sreturn', compact('salesBook', 'customers', 'items'));
    }


    public function sreturn_update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            // dd( $request->quantities);
            // Loop through each item in the request and update the stock and purchase book
            foreach ($request->items as $index => $itemId) {
                $quantity = $request->quantities[$index];
                $amount = $request->rates[$index];
                $tax = $request->taxes[$index];
                $total = $request->totalAmounts[$index];

                // Retrieve the item and validate its existence
                $item = Item::find($itemId);
                if (!$item) {
                    return redirect()->back()->withInput()->withErrors(["Item with ID $itemId does not exist."]);
                }

                // Find the existing PurchesBookItem entry
                $existingPurchesBookItem = SalesBookItem::where('item_id', $itemId)
                    ->where('sales_book_id', $id)
                    ->first();

                if ($existingPurchesBookItem) {
                    $newqty = $quantity;
                    $sreturn = $existingPurchesBookItem->sreturn + $newqty;


                    if ($existingPurchesBookItem->quantity == $existingPurchesBookItem->sreturn && $quantity > 0) {
                        return redirect()->back()->withInput()->withErrors([
                            'error' => "Cannot update. Quantity and return values are already equal for item ID $itemId."
                        ]);
                    }
                } else {
                    // If no existing entry, set sreturn to the current quantity
                    $sreturn = $quantity;
                }

                // if (!$existingPurchesBookItem || $existingPurchesBookItem->sreturn != $quantity) {
                SalesBookItem::updateOrCreate(
                    ['item_id' => $itemId, 'sales_book_id' => $id],
                    [
                        'sreturn' => $sreturn,
                        // 'quantity' => $quantity,
                        'rate' => $amount,
                        'tax' => $tax,
                        'amount' => $total
                    ]
                );
                // }

                $stkqty = $existingPurchesBookItem->quantity - $quantity;
                // Update stock report
                $stockReport = StockReport::where('item_id', $itemId)->first();

                if ($stockReport) {
                    $stockReport->increment('quantity', $quantity);
                }
            }
            // Update the PurchesBook with the calculated grand total
            $salesBook = SalesBook::find($id);
            if ($salesBook) {
                $salesBook->grand_total = $request->grand_total;
                $salesBook->amount_before_tax = $request->amount_before_tax;
                $salesBook->save();
            }

            DB::commit();

            return redirect()->route('company.sales.book.index')->with('success', 'Return Added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while updating the return.']);
        }
    }
}
