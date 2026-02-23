<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:income,expense',
            // amount should be positive
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Validate expense logic against current balance
        if ($validated['type'] === 'expense') {
            $wallet = Wallet::findOrFail($validated['wallet_id']);
            if ($wallet->balance < $validated['amount']) {
                return response()->json([
                    'message' => 'Validation error: Insufficient funds in the wallet.',
                ], 422);
            }
        }

        $transaction = Transaction::create($validated);

        return response()->json([
            'message' => 'Transaction added successfully',
            'data' => $transaction
        ], 201);
    }
}
