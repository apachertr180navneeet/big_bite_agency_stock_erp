<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Company, Tax, Item, ReceiptBookVoucher, SalesBook, Bank};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;

class ReceiptBookVoucherController extends Controller
{
    public function getLastDigit($str) {
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
     * Display the purchase book index page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Simply returning the view for purchase book index page
        return view('company.receipt_book_voucher.index');
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
            ->orderByDesc('receipt_book_vouchers.id')
            ->get();


        // Return the purchase books data as JSON response
        return response()->json(['data' => $ReceiptBookVoucher]);
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

        $companyDetails = Company::find($compId);
        $companyShortCode = $companyDetails->short_code;
        $companyState = $companyDetails->state;

        // Get the maximum invoice number for the company's purchases
        $latestRecieptNumber = ReceiptBookVoucher::where('company_id', $compId)->max('receipt_vouchers_number');
        $lastDigit = $this->getLastDigit($latestRecieptNumber);
        // Generate the next invoice number by incrementing the latest invoice or default to 1
        $lastDigit = (int) $lastDigit; // Convert to integer
        $nextRecieptNumber = $lastDigit ? $lastDigit + 1 : 1;


        // Format the invoice number to have 5 digits, with leading zeros if necessary
        $formattedInvoiceNumber = sprintf('%05d', $nextRecieptNumber);
        $finalInvoiceNumber = $companyShortCode . '-RV' . '-' . $formattedInvoiceNumber;

        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();
        $salesbooks = collect(); // Create an empty collection to store sales books

        foreach ($customers as $customer) {
            $customerSalesbooks = SalesBook::where('customer_id', $customer->id)->get();
            $salesbooks = $salesbooks->merge($customerSalesbooks); // Add the sales books to the collection
        }

        $recieptAmounts = collect();
        foreach ($customers as $customer) {
            $customerReciept = ReceiptBookVoucher::where('customer_id', $customer->id)->get();
            $recieptAmounts = $recieptAmounts->merge($customerReciept); // Add the sales books to the collection
        }

        // dd($salesbooks);
        $banks = Bank::where('company_id', $compId)->get();

        // Pass the vendors and items data to the view for adding a new sales book
        return view('company.receipt_book_voucher.add', compact('customers', 'salesbooks','recieptAmounts', 'finalInvoiceNumber', 'banks'));
    }


    public function store(Request $request)
    {
        try {

            // Define validation rules
            $rules = [
                'date' => 'required|date',
                'receipt' => 'required|string|max:255',
                'customer' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0',
                'discount' => 'required|numeric',
                'bank' => 'nullable|numeric',
                'round_off' => 'required|numeric',
                'grand_total' => 'required|numeric|min:0',
                'remark' => 'required|string|max:500',
                'payment_method' => 'required|string|in:cash,cheque,online bank,other',
            ];

            // Validate the request data
            $validatedData = $request->validate($rules);
            // Get the authenticated user and their company ID
            $user = Auth::user();
            $compId = $user->company_id;
            //cho $validatedData['payment_method']; die;
            // Save the sales book details in the sales_books table
            $salesBook = ReceiptBookVoucher::create([
                'date' => $validatedData['date'],
                'company_id' => $compId,
                'receipt_vouchers_number' => $validatedData['receipt'],
                'customer_id' => $validatedData['customer'],
                'amount' => $validatedData['amount'],
                'discount' => $validatedData['discount'] ?? 0,
                'bank_id' => $validatedData['bank'] ?? 0,
                'round_off' => $validatedData['round_off'] ?? 0,
                'grand_total' => $validatedData['grand_total'],
                'remark' => $validatedData['remark'] ?? '',
                'payment_type' => $validatedData['payment_method'],
            ]);

            // increment the stock quantity by 5
            Bank::where('id', $validatedData['bank'])->increment('opening_blance', $validatedData['amount']);


            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('company.receipt.book.voucher.index')->with('success', 'Receipt book entry saved successfully.');
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
                $ReceiptBook = ReceiptBookVoucher::find($id);

                if (!$ReceiptBook) {
                    throw new \Exception('sales Book not found.');
                }

                // Delete the sales book itself
                $ReceiptBook->delete();
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

        $receiptBook = ReceiptBookVoucher::find($id);

        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        $banks = Bank::where('company_id',$compId)->get();

        return view('company.receipt_book_voucher.edit', compact('receiptBook', 'customers','banks'));
    }


    public function update(Request $request , $id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Get the authenticated user and their company ID
            $user = Auth::user();
            $compId = $user->company_id;
            // Find the specific ReceiptBookVoucher record by its ID
            $receiptBook = ReceiptBookVoucher::findOrFail($id);

            $previousbank = $receiptBook->bank_id;
            $previousamount = $receiptBook->amount;

            // Update the record with the validated data
            $receiptBook->receipt_vouchers_number = $request->receipt;
            $receiptBook->date = $request->date;
            $receiptBook->customer_id = $request->customer;
            $receiptBook->remark = $request->remark;
            $receiptBook->amount = $request->amount;
            $receiptBook->discount = $request->discount;
            $receiptBook->round_off = $request->round_off;
            $receiptBook->bank_id = $request->bank;
            $receiptBook->grand_total = $request->grand_total;
            $receiptBook->payment_type = $request->payment_method;

            // Save the updated record
            $receiptBook->save();

            Bank::where('id', $previousbank)->decrement('opening_blance', $request->amount);


            Bank::where('id', $request->bank)->increment('opening_blance', $request->amount);

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('company.receipt.book.voucher.index')->with('success', 'receipt book entry saved successfully.');
        } catch (\Exception $e) {
            dd($e);
            // Rollback the transaction on error
            DB::rollback();

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while saving the sales book entry.');
        }
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

        return view('company.receipt_book_voucher.print', compact('receiptBook', 'customers'));
    }
}
