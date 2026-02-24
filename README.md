# Money Tracker API

A simple RESTful API built with PHP Laravel to manage users, their multiple wallets, and track income and expense transactions. The API automatically calculates dynamic wallet and user balances.

## Features

- **User Management**: Create a user and view their profile, overall balance, and wallets.
- **Wallet Management**: Create multiple wallets per user and view specific wallet balances with all its transactions.
- **Transaction Tracking**: Add `income` or `expense` transactions to an individual wallet.
- **Validation**:
    - Required fields are strictly enforced.
    - Transaction amounts must be positive numbers.
    - Expense transactions are blocked if the wallet has insufficient funds.

## Prerequisites

- PHP >= 8.2
- Composer
- SQLite (default database)

## Setup Instructions

1. Clone the repository
    ```bash
    git clone <your-repo-url>
    cd money-tracker-api
    ```
2. Install Dependencies
    ```bash
    composer install
    ```
3. Set up the Environment Variables
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Run Database Migrations
    ```bash
    php artisan migrate
    ```
5. Start the Application
    ```bash
    php artisan serve
    ```
    The API will be available at `http://localhost:8000/api`.

## API Endpoints

### 1. Users

**Create a User**

- **Method:** `POST /api/users`
- **Body:**
    ```json
    {
        "name": "Jane Doe",
        "email": "jane@example.com",
        "password": "password123"
    }
    ```

**View User Profile**

- **Method:** `GET /api/users/{id}`
- **Response:** Returns the user details, total balance across all wallets, and an array of their wallets (with individual balances).

### 2. Wallets

**Create a Wallet**

- **Method:** `POST /api/wallets`
- **Body:**
    ```json
    {
        "user_id": 1,
        "name": "Business Account"
    }
    ```

**View Specific Wallet**

- **Method:** `GET /api/wallets/{id}`
- **Response:** Returns the specific wallet, its calculated balance, and an array of all its transactions.

### 3. Transactions

**Add a Transaction**

- **Method:** `POST /api/transactions`
- **Body:**
    ```json
    {
        "wallet_id": 1,
        "type": "income",
        "amount": 5000.5
    }
    ```
    _(Note: `type` must be exactly `"income"` or `"expense"`. `amount` must be greater than 0.)_

## Running Tests

To run the automated PHPUnit tests and verify all API endpoints and business logic:

```bash
php artisan test
```
