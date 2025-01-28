<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
        User,
        Company,
        Variation,
        SubCompany
    };
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;

class VariationController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $subcompany = SubCompany::where('status','active')->get();
        // Pass the company and comId to the view
        return view('company.variation.index',compact('subcompany'));
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

        $variations = Variation::join('sub_company', 'variations.sub_compnay_id', '=', 'sub_company.id')
        ->where('variations.company_id', $compId)
        ->select('variations.*', 'sub_company.name as sub_company_name') // Adjust the select fields as needed
        ->orderBy('variations.id', 'desc')
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
            $User = Variation::findOrFail($request->userId);
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
            Variation::where('id', $id)->delete();

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
            'code' => $request->code,
            'company_id' => $compId,
            'sub_compnay_id' => $request->sub_company
        ];
        Variation::create($dataUser);

        return response()->json([
            'success' => true,
            'message' => 'Category saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = Variation::find($id);
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

        $user = Variation::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'Category Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }

}
