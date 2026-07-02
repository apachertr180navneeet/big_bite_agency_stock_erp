<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{
    User,
    Variation,
    Item,
    city,
    State,
    Pincode
};
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

class CustomerController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $states = State::all();

        // Pass the company and comId to the view
        return view('company.customer.index', compact('states'));
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

        $items = User::where('users.role', 'customer')
        ->where('users.company_id', $compId)->select('users.*') // Adjust the select fields as needed
        ->orderBy('users.id', 'desc')
        ->get();

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
        $user = Auth::user();

        $compId = $user->company_id;
        // Validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'address' => 'nullable|string',
            'gst' => 'nullable|string',
        ];

        // Custom messages
        $messages = [
            'email.unique' => "The email has already been taken in the {$request->role}s.",
            'phone.unique' => "The phone number has already been taken in the {$request->role}s.",
        ];


        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }


        // Save the User data
        $dataUser = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
            'company_id' => $compId,
            'gst_no' =>  $request->gst,
            'password' => Hash::make('12345678'),
        ];
        User::create($dataUser);

        return response()->json([
            'success' => true,
            'message' => 'Item saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = User::find($id);

        return response()->json([
            'user' => $user
        ]);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $request->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $request->id,
            'address' => 'nullable|string',
            'gst_no' => 'nullable|string',
            'id' => 'required|integer|exists:users,id', // Adjust as needed
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->update($request->only(['full_name', 'email', 'phone', 'address', 'gst_no']));
            return response()->json(['success' => true, 'message' => 'User Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }
}
