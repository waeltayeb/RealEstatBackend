<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Property::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'country' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'surface' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'user_username' => 'required|exists:users,username',

        ]);

        // Handle the single image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Handle multiple image uploads
        if ($request->has('images')) {
            $imagePaths = [];
            foreach ($request->images as $image) {
                if ($image->isValid()) {
                    $imagePaths[] = $image->store('images', 'public');
                }
            }
            $validatedData['images'] = json_encode($imagePaths);
        }

        // Create a new Property record with validated data
        $property = Property::create($validatedData);

        // Return a success response with the created data
        return response()->json([
            'message' => 'Property created successfully!',
            'property' => $property,
        ], 201);  // 201 status code means resource was created successfully
    }

    // Other CRUD methods (show, update, destroy) can be added here as needed
}
