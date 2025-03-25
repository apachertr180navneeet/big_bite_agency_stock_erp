<?php

namespace App\Http\Controllers\PagarBook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
        User,
        Company,
        AdvanceSalary,
        EmpSalary
    };
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

class EmployeeController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pass the company and comId to the view
        return view('pagar_book.employee.index');
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

        $compId = null;

        $variation = User::where('role','employee')->orderBy('id', 'desc')->get();
        return response()->json(['data' => $variation]);
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
            $User = User::findOrFail($request->userId);
            $User->status = $request->status;
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
            User::where('id', $id)->delete();

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
            'phone' => 'required|unique:users,phone',
            'doj' => 'required|date',
            'base_salary' => 'required|numeric',
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
        $dataUser = [
            'full_name' => $request->name,
            'phone' => $request->phone,
            'date_of_joing' => $request->doj,
            'base_salary' => $request->base_salary,
            'role' => 'employee'
        ];
        User::create($dataUser);

        return response()->json([
            'success' => true,
            'message' => 'Employee saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'id' => 'required|integer|exists:users,id', // Adjust as needed
        ]);

        // Validation rules
        $rules = [
            'name' => 'required|string',
            'id' => 'required|integer|exists:users,id', // Adjust as needed
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = User::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'User Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }


    // Get advance Salary
    public function getAdvance($id)
    {
        $userId = $id;
        $addvanceSalarys = AdvanceSalary::where('user_id',$id)->orderBy('id', 'desc')->get();


        return view('pagar_book.employee.advance',compact('addvanceSalarys','userId'));
    }

    // Store Advnce Salary
    public function advncestore(Request $request)
    {
        // Validation rules
        $rules = [
            'user_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
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
        $dataUser = [
            'user_id' => $request->user_id,
            'date' => $request->date,
            'amount' => $request->amount,
        ];
        AdvanceSalary::create($dataUser);

        return response()->json([
            'success' => true,
            'message' => 'Employee saved successfully!',
        ]);
    }

    // Get advance Salary
    public function getSalary($id)
    {
        $userId = $id;
        $empSalarys = EmpSalary::where('user_id',$id)->orderBy('id', 'desc')->get();

        $addvanceAmount = AdvanceSalary::where('user_id',$id)->sum('amount');


        return view('pagar_book.employee.salary',compact('empSalarys','userId','addvanceAmount'));
    }
}
