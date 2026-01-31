<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'title',
        'content',
        'is_helpful',
        'helpful_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getStarRatingAttribute()
    {
        return match($this->rating) {
            5 => '⭐⭐⭐⭐⭐',
            4 => '⭐⭐⭐⭐',
            3 => '⭐⭐⭐',
            2 => '⭐⭐',
            1 => '⭐',
            default => 'No Rating'
        };
    }

    public function getFormattedRatingAttribute()
    {
        return match($this->rating) {
            5 => 'Sangat Memuaskan',
            4 => 'Memuaskan',
            3 => 'Cukup Baik',
            2 => 'Kurang Baik',
            1 => 'Tidak Memuaskan',
            default => '-'
        };
    }
}
