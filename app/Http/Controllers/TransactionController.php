<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    public function balance()
    {
        $user = auth()->user(); // Get the authenticated user

        // Log the user ID for debugging purposes
        Log::info('Fetching balance for user ID: ' . $user->id);

        $wallet = Wallet::where('user_id', $user->id)->first();

        // Log the response when no wallet is found
        if (!$wallet) {
            Log::warning('Wallet not found for user ID: ' . $user->id);
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        // Log the wallet balance and currency
        Log::info('Fetched balance:', [
            'user_id' => $user->id,
            'balance' => $wallet->amount,
            'currency' => $wallet->currency ?? 'NGN'
        ]);

        // Return the balance response
        return response()->json([
            'balance' => $wallet->amount,
            'currency' => $wallet->currency ?? 'NGN'
        ]);
    }
    public function verifyAccountId(Request $request, $user_id)
{
    $authUser = auth()->user();

    if ($authUser->user_id == $user_id) {
        return response()->json([
            'success' => false,
            'message' => 'You cannot initiate a transfer to yourself.',
        ]);
    }

    $user = User::where('user_id', $user_id)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found.',
        ]);
    }

    return response()->json([
        'success' => true,
        'full_name' => $user->full_name,
    ]);
}

public function InterTransfer(Request $request)
{
    // Validate the input
    $request->validate([
        'amount' => 'required|numeric|min:0.01', // Ensure the amount is numeric and positive
        'user_id' => 'required|exists:users,user_id', // Validate that the recipient exists
        'description' => 'required|string|max:255', // Description is required
    ]);

    // Fetch the authenticated user's wallet using the user's `id`
    $authUser = Auth::user(); // Get the currently authenticated user
    $authUserWallet = Wallet::where('user_id', $authUser->id)->lockForUpdate()->first(); // Lock the record for update

    // Fetch the recipient's wallet based on user_id
    $recipient = User::where('user_id', $request->user_id)->first();
    $recipientWallet = Wallet::where('user_id', $recipient->id)->lockForUpdate()->first(); // Lock the recipient's record for update

    // Log details before processing
    Log::info('Initiating wallet transfer', [
        'auth_user_id' => $authUser->id,
        'auth_user_balance' => $authUserWallet->balance,
        'recipient_user_id' => $recipient->user_id,
        'recipient_user_name' => $recipient->full_name,
        'transfer_amount' => $request->amount,
        'transfer_description' => $request->description
    ]);

    // Check if the authenticated user has enough balance
    if ($authUserWallet->amount < $request->amount) {
        return response()->json([
            'message' => 'Insufficient funds to complete the transfer.',
        ], 400);
    }

    // Start a database transaction to ensure data consistency
    DB::beginTransaction();

    try {
        // Deduct the amount from the authenticated user's wallet
        $authUserWallet->amount -= $request->amount;
        $authUserWallet->save();

        // Add the amount to the recipient's wallet
        $recipientWallet->amount += $request->amount;
        $recipientWallet->save();

        // Generate transaction ID and insert transaction record
        $transactionId = Str::uuid(); // Generate a unique transaction ID
        Transaction::create([
            'transaction_id' => $transactionId,
            'user_id' => $authUser->id, // User initiating the transaction
            'amount' => $request->amount,
            'type' => 'debit', // Debit for the sender
            'status' => 'successful', // Transaction status
            'method' => 'wallet', // Method is wallet
            'description' => $request->description, // Transaction description
            'account_number' => $recipient->user_id, // The recipient's user_id
            'account_name' => $recipient->full_name, // The recipient's full name
            'bank_name' => 'N/A', // Assuming no bank info needed
        ]);

        // Commit the transaction
        DB::commit();

        // Log details after successful transfer
        Log::info('Wallet transfer successful', [
            'auth_user_id' => $authUser->id,
            'auth_user_new_balance' => $authUserWallet->balance,
            'recipient_user_id' => $recipient->user_id,
            'recipient_user_new_balance' => $recipientWallet->balance,
            'transaction_id' => $transactionId
        ]);

        // Return success response
        return response()->json([
            'message' => 'Transfer successful!',
            'auth_user_balance' => $authUserWallet->amount,
            'recipient_user_balance' => $recipientWallet->amount,
            'transaction_id' => $transactionId, // Return the transaction ID
        ], 200);
    } catch (\Exception $e) {
        // Rollback the transaction in case of an error
        DB::rollBack();

        // Log error details
        Log::error('An error occurred during wallet transfer', [
            'error_message' => $e->getMessage(),
            'auth_user_id' => $authUser->id,
            'recipient_user_id' => $recipient->user_id,
            'transfer_amount' => $request->amount,
            'transfer_description' => $request->description
        ]);

        // Return error response
        return response()->json([
            'message' => 'An error occurred while processing the transfer.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function verifyPin(Request $request)
    {
        // Validate the incoming request (ensure 'pin' is required)
        $request->validate([
            'pin' => 'required|digits:4', // Ensures a 4-digit PIN is provided
        ]);

        // Get the authenticated user (using the token)
        $user = auth()->user();

        // Check if the provided PIN matches the stored hash
        $isValidPin = Hash::check($request->pin, $user->pin);

        if ($isValidPin) {
            return response()->json(['success' => true, 'message' => 'PIN verified successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid PIN'], 401);
        }
    }

}
