<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'is_admin' => 'nullable|boolean',
        'url_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate as an image
        'description' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()
        ]);
    }

    // Handle image upload
    if ($request->hasFile('url_image')) {
        $imageName = Str::random().'.'.$request->url_image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('user/image', $request->url_image, $imageName);
    } else {
        return response()->json(['success' => false, 'message' => 'Image upload failed'], 400);
    }

    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'url_image' => $imageName, // Save the uploaded image name/path
        'description' => $request->description,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    // Automatically log in the user after registration
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered and logged in successfully!',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    return response()->json(['success' => false, 'message' => 'Failed to log in the user after registration.'], 500);
}

        // Login function
        public function login(Request $request)
        {
            // Validation rules for login
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            // Attempt to log the user in using email and password
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid login credentials'
                ], 401);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Generate an authentication token (if using Laravel Sanctum or Passport)
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'user' => $user,
                'token' => $token  // Return the generated token
            ], 200);
        }
        // Logout function
        public function logout()
        {
            // Invalidate the token
            auth()->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful!'
            ], 200);
        }
        public function user()
{
    return response()->json(auth()->user());
}
}
