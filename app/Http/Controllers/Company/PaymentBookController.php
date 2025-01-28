<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Company, Tax, Item, PaymentBook, PurchesBook,Bank};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator, Session};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Exception;

class PaymentBookController extends Controller
{
    /**
     * Display the purchase book index page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Simply returning the view for purchase book index page
        return view('company.payment_book.index');
    }

    /**
     * Fetch all purchase books for the authenticated user's company and return them as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

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
    public function getall(Request $request)
    {
        // Get the authenticated user and their company ID
        $user = Auth::user();
        $compId = $user->company_id;

        // Fetch all purchase books for the user's company, including vendor details
        $ReceiptBookVoucher = PaymentBook::join('users', 'payment_books.vendor_id', '=', 'users.id')
            ->where('payment_books.company_id', $compId)
            ->select('payment_books.*', 'users.full_name as vendor_name')
            ->orderByDesc('payment_books.id')
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
        $latestRecieptNumber = PaymentBook::where('company_id', $compId)->max('payment_vouchers_number');
        $lastDigit = $this->getLastDigit($latestRecieptNumber);
        // Generate the next invoice number by incrementing the latest invoice or default to 1
        $lastDigit = (int) $lastDigit; // Convert to integer
        $nextRecieptNumber = $lastDigit ? $lastDigit + 1 : 1;


        // Format the invoice number to have 5 digits, with leading zeros if necessary
        $formattedInvoiceNumber = sprintf('%05d', $nextRecieptNumber);
        $finalInvoiceNumber = $companyShortCode . '-PV' . '-' . $formattedInvoiceNumber;

        // Fetch all active vendors for the user's company
        $vendors = User::where('role', 'vendor')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();


        $purchasebooks = collect(); // Create an empty collection to store sales books

        foreach ($vendors as $vendor) {
            $customerPurchaseBooks = PurchesBook::where('vendor_id', $vendor->id)->get();
            $purchasebooks = $purchasebooks->merge($customerPurchaseBooks); // Add the sales books to the collection
        }

        $paymentAmounts = collect();
        foreach ($vendors as $vendor) {
            $customerReciept = PaymentBook::where('vendor_id', $vendor->id)->get();
            $paymentAmounts = $paymentAmounts->merge($customerReciept); // Add the sales books to the collection
        }

        // dd($salesbooks);
        $banks = Bank::where('company_id',$compId)->get();
        // Pass the vendors and items data to the view for adding a new sales book
        return view('company.payment_book.add', compact('vendors','purchasebooks','paymentAmounts','finalInvoiceNumber','banks'));
    }


    public function store(Request $request)
    {
        try {

            // Define validation rules
            $validatedData = $request->validate([
                'date' => 'required',
                'payment' => 'required|string|max:255',
                'vendor' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0',
                'round_off' => 'required|numeric',
                'grand_total' => 'required|numeric',
                'remark' => 'required|string',
                'payment_method' => 'required|string|in:cash,cheque,online bank,other',
            ]);

            // Start a database transaction
            DB::beginTransaction();
            // Get the authenticated user and their company ID
            $user = Auth::user();
            $compId = $user->company_id;

            // Save the payment book details in the payment_books table
            $salesBook = PaymentBook::create([
                'date' => $request->date,
                'company_id' => $compId,
                'payment_vouchers_number' => $request->payment,
                'vendor_id' => $request->vendor,
                'amount' => $request->amount,
                'discount' => $request->discount,
                'round_off' => $request->round_off,
                'bank_id' => $request->bank ?? 0,
                'grand_total' => $request->grand_total,
                'remark' => $request->remark,
                'payment_type' => $request->payment_method,
            ]);


            // increment the stock quantity by 5
            Bank::where('id', $request->bank)->decrement('opening_blance', $request->amount);

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('company.payment.book.index')->with('success', 'Payment book entry saved successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            dd($e);

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while saving the payment book entry.');
        }
    }



    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Find the sales book
                $ReceiptBook = PaymentBook::find($id);

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

        $paymentBook = PaymentBook::find($id);

        // Fetch all active vendors for the user's company
        $vendors = User::where('role', 'vendor')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        // dd($salesbooks);
        $banks = Bank::where('company_id',$compId)->get();

        return view('company.payment_book.edit', compact('paymentBook', 'vendors','banks'));
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
            $receiptBook = PaymentBook::findOrFail($id);

            $previousbank = $receiptBook->bank_id;
            $previousamount = $receiptBook->amount;

            // Update the record with the validated data
            $receiptBook->payment_vouchers_number = $request->payment;
            $receiptBook->date = $request->date;
            $receiptBook->vendor_id = $request->vendor;
            $receiptBook->remark = $request->remark;
            $receiptBook->amount = $request->amount;
            $receiptBook->discount = $request->discount;
            $receiptBook->round_off = $request->round_off;
            $receiptBook->bank_id = $request->bank;
            $receiptBook->grand_total = $request->grand_total;
            $receiptBook->payment_type = $request->payment_method;

            // Save the updated record
            $receiptBook->save();

            Bank::where('id', $previousbank)->increment('opening_blance', $request->amount);


            Bank::where('id', $request->bank)->decrement('opening_blance', $request->amount);

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('company.payment.book.index')->with('success', 'Payment book entry saved successfully.');
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

        $paymentBook = PaymentBook::join('users', 'payment_books.vendor_id', '=', 'users.id')
        ->select('payment_books.*', 'users.full_name as vendor_name')
        ->find($id);

        // Fetch all active vendors for the user's company
        $customers = User::where('role', 'customer')
            ->where('company_id', $compId)
            ->where('status', 'active')
            ->get();

        return view('company.payment_book.print', compact('paymentBook', 'customers'));
    }
}
