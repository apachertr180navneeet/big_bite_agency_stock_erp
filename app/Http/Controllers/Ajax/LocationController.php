<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    User,
    Variation,
    Item,
    city,
    State,
    Pincode,
    StockReport,
    SubCompany,
    PurchesBook, 
    PurchesBookItem
};

class LocationController extends Controller
{
    public function getCities($state)
    {
        $cities = City::where('state_id', $state)->get(['id', 'city_name']);
        return response()->json($cities);
    }

    public function getPincodes($city)
    {
        $pincodes = Pincode::where('city_id', $city)->get();
        return response()->json($pincodes);
    }

    public function checkStock(Request $request)
    {
        $item = StockReport::where('item_id',$request->item_id)->first();

        // Assuming the item model has a 'stock' column for available stock
        if ($item && $item->quantity >= $request->quantity) {
            return response()->json(['stock_available' => true]);
        } else {
            return response()->json(['stock_available' => false]);
        }
    }

    // Method to get all states
    public function getStates()
    {
        $states = State::all(); // Fetch all states from your database
        return response()->json($states);
    }

    // Method to get cities by state
    public function getCitiesByState($stateName)
    {
        $state = State::where('state_name', $stateName)->first();
        if ($state) {
            $cities = City::where('state_id', $state->state_id)->get(); // Fetch cities by state ID
            return response()->json($cities);
        }
        return response()->json([], 404); // Return empty if state not found
    }

    // Method to get zip codes by city
    public function getZipcodesByCity($cityName)
    {
        $city = City::where('city_name', $cityName)->first();
        if ($city) {
            $zipcodes = Pincode::where('city_id', $city->id)->get(); // Fetch zip codes by city ID
            return response()->json($zipcodes);
        }
        return response()->json([], 404); // Return empty if city not found
    }

    // Manage to get category by sub company
    public function getCategory($sub_company)
    {
        $categories = Variation::where('sub_compnay_id', $sub_company)->get(['id', 'name']);
        return response()->json($categories);
    }

    public function getVendors($sub_company_id)
    {
        $vendors = User::where('sub_compnay_id', $sub_company_id)->where('role', 'vendor')->where('status', 'active')->get();

        return response()->json($vendors);
    }


    public function getCategories($sub_company_id)
    {
        $categories = Variation::where('sub_compnay_id', $sub_company_id)->where('status', 'active')->get();

        return response()->json($categories);
    }

    public function getItems($category_id)
    {
        $items = Item::with(['variation:id,name', 'tax:id,rate'])->where('variation_id', $category_id)->get(['id', 'name', 'tax_id' , 'company_id' , 'variation_id', 'hsn_hac']);
        return response()->json($items);
    }

    public function getCustomers($sub_company_id)
    {
        $customers = User::where('sub_compnay_id', $sub_company_id)->where('role', 'customer')->where('status', 'active')->get();

        return response()->json($customers);
    }

    public function getPurchaseDetails($id)
    {
        $purchase = PurchesBook::find($id);
        if (!$purchase) {
            return response()->json(['success' => false, 'message' => 'Purchase not found.']);
        }

        // Fetch related purchase items
        $items = PurchesBookItem::where('purches_book_id', $id)->get()->map(function ($item) {
            return [
                'item_id' => $item->item_id,
                'item_name' => $item->item->name,
                'current_quantity' => $item->quantity - $item->preturn,
                'preturn' => $item->preturn, // Adjusted quantity
                'variation' => $item->item->variation->name ?? '-',
                'rate' => $item->rate,
                'tax' => $item->tax,
                'amount' => $item->amount
            ];
        });

        return response()->json([
            'success' => true,
            'purchase' => [
                'date' => $purchase->date,
                'amount_before_tax' => $purchase->amount_before_tax,
                'igst' => $purchase->igst,
                'cgst' => $purchase->cgst,
                'sgst' => $purchase->sgst,
                'other_expense' => $purchase->other_expense,
                'discount' => $purchase->discount,
                'round_off' => $purchase->round_off,
                'grand_total' => $purchase->grand_total
            ],
            'items' => $items
        ]);
    }
}
