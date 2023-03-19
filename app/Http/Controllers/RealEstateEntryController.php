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
        $validatedData = $request->validate($rules);
        

        // Build the query to search for entries
        $query = RealEstateEntry::query();

        if (isset($validatedData['type'])) {
            $query->where('real_estate_entries_type', $validatedData['type']);
        }

        if (isset($validatedData['address'])) {
            $query->where('real_estate_entries_address', 'like', '%'.$validatedData['address'].'%');
        }

        if (isset($validatedData['size'])) {
            $query->where('real_estate_entries_size', '>=', $validatedData['size']);
        }

        if (isset($validatedData['number_of_bedrooms'])) {
            $query->where('real_estate_entries_number_of_bedrooms', $validatedData['number_of_bedrooms']);
        }

        if (isset($validatedData['min_price'])) {
            $query->where('real_estate_entries_price', '>=', $validatedData['min_price']);
        }

        if (isset($validatedData['max_price'])) {
            $query->where('real_estate_entries_price', '<=', $validatedData['max_price']);
        }

        // Get the results
        $results = $query->get();

        // Return the results
        return response()->json($results);
    }

    public function radiusSearch(Request $request): JsonResponse
    {
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
