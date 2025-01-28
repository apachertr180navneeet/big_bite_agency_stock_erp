<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Company,
    city,
    State,
    Pincode
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class CompanyController extends Controller
{
    /**
     * Display the company index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $states = State::all();
        // Pass the company and comId to the view
        return view('admin.company.index', compact('states'));
    }

    /**
     * Fetch all companies and return as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCompany(Request $request)
    {
        $companies = Company::orderBy('id', 'desc')->get();
        return response()->json(['data' => $companies]);
    }

    /**
     * Update the status of a company.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyStatus(Request $request)
    {
        try {
            $company = Company::find($request->userId);
            $company->status = $request->status;
            $company->save();

            return response()->json(['success' => true , 'message' => 'Comapny Status Updated Successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a company by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Company::where('id', $id)->delete();
            User::where('company_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Company deleted successfully',
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
            'email' => 'required|email|max:255|unique:companies',
            'phone' => 'required|string|max:20|unique:companies',
            'address' => 'nullable|string',
            'short_code' => 'required|string',
            'city' => 'required|string|max:100',
            'type' => 'required|string|in:type1,type2', // Adjust types as necessary
            'gstin' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Save the company data
        $company = Company::create($request->only([
            'name', 'email', 'phone', 'address', 'city', 'type','gstin','short_code','state','zipcode'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Company saved successfully!',
        ]);
    }

    // Fetch user data
    public function getCompany($id)
    {
        $user = Company::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Retrieve state data based on state name from user
        $stateData = State::where('state_name', $user->state)->first();

        // Retrieve city data based on city name from user
        $cityData = City::where('city_name', $user->city)->first();


        // Retrieve cities based on state id
        $cities = City::where('state_id', $stateData->state_id ?? null)->get(); // Safeguard in case state data is not found

        // Retrieve pincodes based on city id
        $pincodes = Pincode::where('city_id', $cityData->id ?? null)->get(); // Safeguard in case city data is not found

        return response()->json([
            'company' => $user,
            'state' => $stateData,
            'city' => $cityData,
            'cities' => $cities,
            'pincodes' => $pincodes
        ]);

    }


    public function show($id)
    {
        $user = Company::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Retrieve state data based on state name from user
        $stateData = State::where('state_name', $user->state)->first();

        // Retrieve city data based on city name from user
        $cityData = City::where('city_name', $user->city)->first();


        // Retrieve cities based on state id
        $cities = City::where('state_id', $stateData->state_id ?? null)->get(); // Safeguard in case state data is not found

        // Retrieve pincodes based on city id
        $pincodes = Pincode::where('city_id', $cityData->id ?? null)->get(); // Safeguard in case city data is not found


        return view('admin.company.show', compact('user', 'stateData', 'cityData', 'cities', 'pincodes'));

    }


    public function logo(Request $request)
    {
        $comId = $request->id;

        return view('admin.company.logo', compact('comId'));


    }

    // Update user data
    public function updateCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'type' => 'required|string',
            'gstin' => 'required|string',
            'short_code' => 'required|string',
            'id' => 'required|integer|exists:companies,id', // Adjust as needed
        ]);

        $user = Company::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'Company Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }


    public function updatelogo(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id' => 'required|exists:companies,id', // Ensure the ID exists
        ]);

        // Find the company
        $company = Company::find($request->id);

        if ($company) {
            // Check if a file is uploaded
            if ($request->hasFile('image')) {
                // Generate a unique file name
                $fileName = time() . '.' . $request->file('image')->getClientOriginalExtension();

                // Move the file to public/uploads
                $request->file('image')->move(public_path('uploads'), $fileName);

                // Get the full URL of the uploaded file
                $fullUrl = url('uploads/' . $fileName);

                // Update the company's logo field with the full URL
                $company->logo = $fullUrl;
            }

            // Update other fields if provided
            $company->update($request->except('image'));

            return redirect()->back()->with('success', 'Company logo updated successfully!');
        }

        return redirect()->back()->with('error', 'Company not found.');
    }


}
