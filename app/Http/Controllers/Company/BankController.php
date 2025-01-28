<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Bank
};
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

use Illuminate\Validation\Rule;

class BankController extends Controller
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

        // Pass the company and comId to the view
        return view('company.bank.index');
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

        $items = Bank::where('company_id',$compId)->get();

        return response()->json(['data' => $items]);
    }

    /**
     * Update the status of a User.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        try {
            $User = Bank::findOrFail($request->userId);
            $User->status = $request->status;
            $User->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function show_invoice(Request $request)
    {
        try {
            $user = Auth::user();
            $compId = $user->company_id;
            // Sab banks ka show_invoice 0 set kar rahe hain
            Bank::where('company_id', $compId)->update(['show_invoice' => '0']);

            // Ab specified userId wale bank ka show_invoice 1 karenge
            $User = Bank::findOrFail($request->userId);
            $User->show_invoice = '1';
            $User->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
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
            Bank::where('id', $id)->delete();

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

    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'acc_no' => 'required|string|unique:banks,account_number',  // Unique validation
            'ifsc_code' => 'required|string',
            'branch' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255'
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
        // Save the User data
        $dataBank = [
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->acc_no,
            'ifsc_code' => $request->ifsc_code,
            'branch_name' => $request->branch,
            'company_id' => $compId,
            'opening_blance' => $request->opening_blance
        ];
        Bank::create($dataBank);

        return response()->json([
            'success' => true,
            'message' => 'Bank saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = Bank::find($id);
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'bank_name' => 'required',
            'account_number' => [
                'required',
                Rule::unique('banks', 'account_number')->ignore($request->id), // Ensure account number is unique, ignoring the current record
            ],
            'ifsc_code' => 'required',
            'branch_name' => 'required',
            'id' => 'required|integer|exists:banks,id', // Make sure to validate that the id exists in the correct table
        ]);

        $user = Bank::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'User Updated Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }
}
