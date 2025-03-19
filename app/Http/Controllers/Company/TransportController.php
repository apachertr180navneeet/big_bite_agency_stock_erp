<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
        User,
        Company,
        Transport,
    };
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

class TransportController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pass the company and comId to the view
        return view('company.transport.index');
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
        
        $variations = Transport::where('company_id', $compId)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json(['data' => $variations]);
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
            $User = Transport::findOrFail($request->userId);
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
            Transport::where('id', $id)->delete();

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
            // 'code' => 'required|max:255|unique:variations',
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
            'name' => $request->name,
            'company_id' => $compId,
        ];
        Transport::create($dataUser);

        return response()->json([
            'success' => true,
            'message' => 'Category saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = Transport::find($id);
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            // 'code' => 'required|max:255',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = Transport::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'Category Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }

}
