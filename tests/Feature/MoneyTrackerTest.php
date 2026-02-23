<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyTrackerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_workflow()
    {
        // Create user
        $response = $this->postJson('/api/users', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(201);
        $userId = $response->json('data.id');

        // Create Wallet 1
        $response = $this->postJson('/api/wallets', [
            'user_id' => $userId,
            'name' => 'Business Wallet',
        ]);
        $response->assertStatus(201);
        $wallet1Id = $response->json('data.id');

        // Create Wallet 2
        $response = $this->postJson('/api/wallets', [
            'user_id' => $userId,
            'name' => 'Personal Wallet',
        ]);
        $wallet2Id = $response->json('data.id');

        // Add income to Wallet 1
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet1Id,
            'type' => 'income',
            'amount' => 1000,
        ])->assertStatus(201);

        // Add expense to Wallet 1
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet1Id,
            'type' => 'expense',
            'amount' => 250,
        ])->assertStatus(201);

        // Failed expense to Wallet 1 (insufficient balance)
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet1Id,
            'type' => 'expense',
            'amount' => 1000,
        ])->assertStatus(422);

        // Add income to Wallet 2
        $this->postJson('/api/transactions', [
            'wallet_id' => $wallet2Id,
            'type' => 'income',
            'amount' => 500,
        ])->assertStatus(201);

        // View Wallet 1
        $response = $this->getJson("/api/wallets/{$wallet1Id}");
        $response->assertStatus(200);
        $this->assertEquals(750, $response->json('data.balance'));
        $this->assertCount(2, $response->json('data.transactions'));

        // View User
        $response = $this->getJson("/api/users/{$userId}");
        $response->assertStatus(200);
        $this->assertEquals(1250, $response->json('data.balance')); // 750 + 500
        $this->assertCount(2, $response->json('data.wallets'));
    }
}
