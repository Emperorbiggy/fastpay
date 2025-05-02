<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate unique user_id
        $user_id = $this->generateUniqueUserId();
        $verification_code = 1234; // You should replace this with a dynamic OTP generation (e.g., a random 4-digit number)

        // Create user
        $user = User::create([
            'user_id' => $user_id,
            'full_name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => $verification_code,
            'is_verified' => 0,
        ]);

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Store token in DB
        $user->token = $token;
        $user->save();

        // Return response
        return response()->json([
            'message' => 'User registered successfully!',
            'user_id' => $user->user_id,
            'token' => $token,
            'user' => $user
        ], 201);
    }

    /**
     * Verify the OTP entered by the user.
     */
    public function verifyOtp(Request $request)
    {
        // Get token from Authorization header
        $bearerToken = $request->bearerToken();

        // Validate OTP input
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        // Get the user using the token from the Authorization header
        $user = User::where('token', $bearerToken)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token.'], 404);
        }

        // Check if the OTP matches
        if ($user->verification_code != $request->otp) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        // OTP is valid, update the user status to verified
        $user->is_verified = 1;
        $user->save();

        return response()->json([
            'message' => 'Phone number verified successfully!',
            'user' => $user
        ]);
    }

    /**
     * Update user information.
     */
    public function updateUserInfo(Request $request)
    {
        // Get user from the token (assuming you're using Sanctum or Passport)
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        // Validate the request
        $request->validate([
            'address' => 'required|string|max:255',
            'state'   => 'required|string|max:100',
            'city'    => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        // Update the user info
        $user->address = $request->address;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->save();

        return response()->json([
            'message' => 'User information updated successfully!',
            'user' => $user
        ]);
    }
    public function updatePin(Request $request)
{
    // Get token from request headers
    $token = $request->bearerToken();
    Log::info('Token received:', ['token' => $token]);

    // Get authenticated user
    $user = Auth::user();

    if (!$user) {
        Log::warning('No authenticated user found.');
        return response()->json(['message' => 'Unauthorized.'], 401);
    }

    // Log the authenticated user object
    Log::info('Authenticated User:', $user->toArray());

    // Validate the PIN
    $request->validate([
        'pin' => 'required|array|size:4',
    ]);

    // Convert PIN array to string and hash it
    $pinString = implode('', $request->pin);
    $user->pin = Hash::make($pinString);
    $user->save();

    // Try to fetch the user from the DB manually
    $dbUser = DB::table('users')->where('id', $user->id)->first();

    if (!$dbUser) {
        Log::error('User not found in DB after Auth:', ['user_id' => $user->id]);
        return response()->json(['message' => 'User not found.'], 404);
    }

    Log::info('User fetched from DB:', (array) $dbUser);

    // Check if wallet exists
    $walletExists = DB::table('wallets')->where('user_id', $user->id)->exists();

    if (!$walletExists) {
        try {
            DB::table('wallets')->insert([
                'user_id' => $user->id,
                'amount' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet creation failed:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating wallet: ' . $e->getMessage()], 500);
        }
    }

    return response()->json([
        'message' => 'Transaction PIN created successfully!',
        'user' => $user,
    ]);
}




    /**
     * Generate a unique user ID.
     */
    public function generateUniqueUserId()
{
    // Get the current year (4 digits) and day of the year (3 digits)
    $year = date('Y'); // Get current year, e.g., "2025"
    $dayOfYear = str_pad(date('z') + 1, 3, '0', STR_PAD_LEFT); // Get day of year, e.g., "150"

    // Generate a random 4-digit number
    $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // Ensure it's 4 digits, e.g., "1234"

    // Concatenate to form the user_id
    $userId = $year . $dayOfYear . $randomNumber;

    // Check for uniqueness (optional, but recommended)
    while (User::where('user_id', $userId)->exists()) {
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // Regenerate the random part if not unique
        $userId = $year . $dayOfYear . $randomNumber;
    }

    return $userId;
}

}
