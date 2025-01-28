<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    User,
    BankAndCash,
    Bank
};
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

class BankAndCashMangementController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $compId = $user->company_id;

        $bankLists = Bank::where('company_id', $compId)->get();
        // dd($bankLists);

        return view('company.bankandcash.index', compact('bankLists'));
    }

    /**
     * Fetch all companies and return as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getall(Request $request)
    {
        $user = Auth::user();

        $compId = $user->company_id;

        $bankandcash = BankAndCash::where('bank_and_cashes.company_id', $compId)
            ->leftJoin('banks as deposite_bank', 'deposite_bank.id', '=', 'bank_and_cashes.deposite_in') // Join for deposite_in
            ->leftJoin('banks as withdraw_bank', 'withdraw_bank.id', '=', 'bank_and_cashes.withdraw_in') // Join for withdraw_in
            ->select(
                'bank_and_cashes.*',
                'deposite_bank.bank_name as deposite_bank_name',  // Select the name of the bank for deposite_in
                'withdraw_bank.bank_name as withdraw_bank_name'   // Select the name of the bank for withdraw_in
            )
            ->get();

        return response()->json(['data' => $bankandcash]);
    }


    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'date' => 'required',
            'serial_no' => 'required|string|unique:bank_and_cashes',
            'amount' => 'required',
            'deposite_in' => 'required',
            'withdraw_in' => 'required',
            'description' => 'required',
            'particular' => 'required'
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = Auth::user();

        $compId = $user->company_id;
        $amount = $request->amount;
 
        // Save the User data
        $dataUser = [
            'date' => $request->date,
            'serial_no' => $request->serial_no,
            'amount' => $request->amount,
            'deposite_in' => $request->deposite_in,
            'withdraw_in' => $request->withdraw_in,
            'description' => $request->description,
            'particular' => $request->particular,
            'company_id' => $compId
        ];
        BankAndCash::create($dataUser);

        $depositedBank = Bank::where('id', $request->deposite_in)
        ->where('company_id', $compId)
        ->first(); 
        // dd($depositedBank->opening_blance);
        if ($depositedBank) {
            $depositedBank->opening_blance += $amount;  
            $depositedBank->save(); 
        }

        $withrawalBank = Bank::where('id', $request->withdraw_in)
            ->where('company_id', $compId)
            ->first();
        if ($withrawalBank) {
            $withrawalBank->opening_blance -= $amount; // Increment the opening balance
            $withrawalBank->save(); 
        }

        return response()->json([
            'success' => true,
            'message' => 'Bank And Cash saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $bankCash = BankAndCash::find($id);
        // Fetch the list of banks (assuming you have a Bank model)
        $banks = Bank::all();

        return response()->json([
            'bankCash' => $bankCash,
            'banks' => $banks
        ]);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'serial_no' => 'required|string',
            'amount' => 'required',
            'deposite_in' => 'required',
            'withdraw_in' => 'required',
            'description' => 'required',
            'particular' => 'required',
            'id' => 'required|integer|exists:bank_and_cashes,id', // Adjust as needed
        ]);

        $user = BankAndCash::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true, 'message' => 'User Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    /**
     * Delete a User by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            BankAndCash::where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
