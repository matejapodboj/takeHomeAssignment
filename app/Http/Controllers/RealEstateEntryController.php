<?php

namespace App\Http\Controllers;

use App\Models\RealEstateEntry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RealEstateEntryController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        // Define the validation rules
        $rules = [
            'real_estate_entries_type' => 'required|in:apartment,house',
            'real_estate_entries_address' => 'required|max:255',
            'real_estate_entries_size' => 'required|numeric',
            'real_estate_entries_number_of_bedrooms' => 'required|integer',
            'real_estate_entries_price' => 'required|numeric',
            'real_estate_entries_latitude' => 'nullable|numeric',
            'real_estate_entries_longitude' => 'nullable|numeric',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Return a JSON response with validation errors if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new RealEstateEntry instance
        $entry = new RealEstateEntry;

        // Set the type, address, number of bedrooms, price, and geographical location
        $entry->real_estate_entries_type = $request->input('real_estate_entries_type');
        $entry->real_estate_entries_address = $request->input('real_estate_entries_address');
        $entry->real_estate_entries_size = $request->input('real_estate_entries_size');
        $entry->real_estate_entries_number_of_bedrooms = $request->input('real_estate_entries_number_of_bedrooms');
        $entry->real_estate_entries_price = $request->input('real_estate_entries_price');
        $entry->real_estate_entries_latitude = $request->input('real_estate_entries_latitude');
        $entry->real_estate_entries_longitude = $request->input('real_estate_entries_longitude');

        // Save the new entry to the database
        $entry->save();

        return response()->json($entry);
    }

    public function search(Request $request)
    {
        // Define the validation rules
        $rules = [
            'type' => 'nullable|in:apartment,house',
            'address' => 'nullable|string',
            'size' => 'nullable|numeric',
            'number_of_bedrooms' => 'nullable|numeric',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If the validation fails, return a response with the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Build the query to search for entries
        $query = RealEstateEntry::query();

        if ($request->has('type')) {
            $query->where('real_estate_entries_type', $request->get('type'));
        }

        if ($request->has('address')) {
            $query->where('real_estate_entries_address', 'like', '%'.$request->get('address').'%');
        }

        if ($request->has('size')) {
            $query->where('real_estate_entries_size', '>=', $request->get('size'));
        }

        if ($request->has('number_of_bedrooms')) {
            $query->where('real_estate_entries_number_of_bedrooms', $request->get('number_of_bedrooms'));
        }

        if ($request->has('min_price')) {
            $query->where('real_estate_entries_price', '>=', $request->get('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('real_estate_entries_price', '<=', $request->get('max_price'));
        }

        // Get the results
        $results = $query->get();

        // Return the results
        return response()->json($results);
    }


    public function radiusSearch(Request $request): JsonResponse
    {

        // Define the validation rules
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If the validation fails, return a response with the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get the search parameters from the request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius');

        // Search for real estate entries within the given radius
        $realEstateEntry = new RealEstateEntry();
        $entries = $realEstateEntry->searchWithinRadius($latitude, $longitude, $radius);

        // Return the results as a JSON response
        return response()->json($entries);
    }

}
