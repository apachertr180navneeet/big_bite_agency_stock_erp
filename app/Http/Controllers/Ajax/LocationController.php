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
}
