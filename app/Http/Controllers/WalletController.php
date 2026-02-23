<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        $wallet = Wallet::create($validated);

        return response()->json([
            'message' => 'Wallet created successfully',
            'data' => $wallet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        // Eager load transactions for the wallet
        $wallet->load('transactions');
        
        return response()->json([
            'data' => $wallet,
        ]);
    }
}
