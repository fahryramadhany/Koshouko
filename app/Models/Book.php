<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'isbn', 'title', 'description', 'author', 'publisher',
        'publication_year', 'category_id', 'cover_image', 'total_copies',
        'available_copies', 'status', 'location', 'pages', 'language'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    public function getAverageRatingAttribute()
    {
        $average = $this->reviews()->avg('rating');
        return round($average, 1);
    }

    public function getRatingCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $this->reviews()->where('rating', $i)->count();
            $distribution[$i] = $count;
        }
        return $distribution;
    }

    public function getIsAvailableAttribute()
    {
        return $this->available_copies > 0;
    }
}
