<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['borrowing_id', 'user_id', 'amount', 'status', 'reason', 'paid_at'];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
