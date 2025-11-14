<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $appointment_id
 * @property int $specialist_id
 * @property int|null $user_id
 * @property string|null $client_name
 * @property int $rating
 * @property string $comment
 * @property string|null $specialist_response
 * @property bool $is_approved
 * @property bool $is_featured
 * @property array|null $photos
 * @property int|null $service_quality_rating
 * @property int|null $punctuality_rating
 * @property int|null $cleanliness_rating
 * @property int|null $overall_experience
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'specialist_id',
        'user_id',
        'client_name',
        'rating',
        'comment',
        'specialist_response',
        'is_approved',
        'is_featured',
        'photos',
        'service_quality_rating',
        'punctuality_rating',
        'cleanliness_rating',
        'overall_experience'
    ];

    protected $casts = [
        'rating' => 'integer',
        'service_quality_rating' => 'integer',
        'punctuality_rating' => 'integer', 
        'cleanliness_rating' => 'integer',
        'overall_experience' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'photos' => 'array'
    ];

    // Relatii
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false)->whereNull('specialist_response');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Metode helper
    public function getStarsHtmlAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    public function getAverageDetailedRatingAttribute()
    {
        $ratings = [
            $this->service_quality_rating,
            $this->punctuality_rating,
            $this->cleanliness_rating,
            $this->overall_experience
        ];
        
        $validRatings = array_filter($ratings, function($rating) {
            return $rating !== null && $rating > 0;
        });
        
        return !empty($validRatings) ? array_sum($validRatings) / count($validRatings) : $this->rating;
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d.m.Y');
    }
}