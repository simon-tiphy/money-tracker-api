<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'name'];

    protected $appends = ['balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute()
    {
        if ($this->relationLoaded('transactions')) {
            $income = $this->transactions->where('type', 'income')->sum('amount');
            $expense = $this->transactions->where('type', 'expense')->sum('amount');
            return $income - $expense;
        }

        $income = $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->transactions()->where('type', 'expense')->sum('amount');
        return $income - $expense;
    }
}
