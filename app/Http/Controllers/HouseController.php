<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return House::all();
    }

    public function getHouses() {
        // Fetch houses with the related agent data
        $houses = House::with('user')->get();

        // Format the response as per your desired structure
        $formattedHouses = $houses->map(function ($house) {
            return [
                'id' => $house->id,
                'type' => $house->type,
                'name' => $house->name,
                'description' => $house->description,
                'image' => $house->image,
                'images' => $house->images,
                'country' => $house->country,
                'address' => $house->address,
                'bedrooms' => $house->bedrooms,
                'bathrooms' => $house->bathrooms,
                'surface' => $house->surface,
                'price' => $house->price,
                'user' => [
                    'username' => $house->user->username,
                    'image' => $house->user->url_image,
                    'name' => $house->user->name,
                    'phone' => $house->user->phone,
                    'address' => $house->user->address,
                ]
            ];
        });

        return response()->json($formattedHouses);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'country' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'surface' => 'nullable|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'user_username' => 'required|exists:users,username',
        ]);

        // Return validation errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        // Handle single image upload
        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('house/image', $request->image, $imageName);

       // Handle multiple image uploads
        $imageNames = [];
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                if ($image->isValid()) {
                    // Store the image and get the full path
                    $path = $image->store('house/images', 'public');

                    // Extract and store only the image name (basename)
                    $imageNames[] = basename($path);
                }
            }
        }
        // Create the house entry in the database
        $house = House::create([
            'name' => $request->name,
            'address' => $request->address,
            'price' => $request->price,
            'description' => $request->description,
            'country' => $request->country,
            'type' => $request->type,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'surface' => $request->surface,
            'image' => $imageName,
            'images' => json_encode($imageNames), // Storing multiple images as a JSON array
            'user_username' => $request->user_username,
        ]);

        return response()->json([
            'success' => true,
            'data' => $house,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, House $house)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        //
    }
}
