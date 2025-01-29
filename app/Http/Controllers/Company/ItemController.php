<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
        User,
        Variation,
        Item,
        Tax,
        StockReport,
        SubCompany
    };
use Mail, DB, Hash, Validator, Session, File, Exception, Redirect, Auth;
use Illuminate\Validation\Rule;

class ItemController extends Controller
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

        $variation = Variation::where('company_id',$compId)->where('status','active')->orderBy('id', 'desc')->get();
        $taxs = Tax::where('company_id',null)->where('status','active')->orderBy('id', 'desc')->get();
        $subcompanys = SubCompany::where('company_id',$compId)->where('status','active')->orderBy('id', 'desc')->get();
        // Pass the company and comId to the view
        return view('company.item.index', compact('variation','taxs','subcompanys'));
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

        $items = Item::join('variations', 'items.variation_id', '=', 'variations.id')
        ->join('sub_company','items.sub_compnay_id','=','sub_company.id')
        ->where('items.company_id',$compId)
        ->select('items.*', 'variations.name as variation_name' , 'sub_company.name as sub_company_name')
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
            $User = Item::findOrFail($request->userId);
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
            Item::where('id', $id)->delete();

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
            'name' => 'required|string',
            'description' => 'required',
            'variation_id' => 'required',
            'sub_comapny' => 'required',
            'tax_id' => 'required',
            'hsn_hac' => 'required|unique:items,hsn_hac',
            'opening_stock' => 'required',
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
            'description' => $request->description,
            'variation_id' => $request->variation_id,
            'sub_compnay_id' => $request->sub_comapny,
            'tax_id' => $request->tax_id,
            'hsn_hac' => $request->hsn_hac,
            'company_id' =>  $compId
        ];
        $item = Item::create($dataUser);
        $itemId = $item->id;

        StockReport::create([
            'item_id' => $itemId,
            'quantity' => $request->opening_stock,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Item saved successfully!',
        ]);
    }

    // Fetch user data
    public function get($id)
    {
        $user = Item::find($id);

        $stockReport = StockReport::where('item_id', $id)->first();

        // Retrieve cities based on state id
        $categories = Variation::where('sub_compnay_id', $user->sub_compnay_id)->get(['id', 'name']);

        $user->quantity = $stockReport->quantity;
        return response()->json([
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'variation_id' => 'required',
            'sub_compnay_id' => 'required',
            'id' => 'required|integer|exists:items,id', // Adjust as needed
            'hsn_hac' => 'required',
            'hsn_hac' => [
                'required',
                Rule::unique('items', 'hsn_hac')->ignore($request->id), // Ensure account number is unique, ignoring the current record
            ],
        ]);

        $user = Item::find($request->id);
        if ($user) {
            $user->update($request->all());
            $stockReport = StockReport::where('item_id', $request->id)->first();
            if ($stockReport) {
                $stockReport->quantity = $request->stock;
                $stockReport->save();
            } else {
                StockReport::create([
                    'item_id' => $request->id,
                    'quantity' => $request->stock,
                ]);
            }
            return response()->json(['success' => true , 'message' => 'Item Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

}
