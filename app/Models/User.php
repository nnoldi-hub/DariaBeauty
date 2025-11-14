<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $profile_image
 * @property string|null $description
 * @property string|null $slug
 * @property string|null $sub_brand
 * @property array|null $coverage_area
 * @property array|null $mobile_equipment
 * @property float|null $transport_fee
 * @property int|null $max_distance
 * @property bool $is_active
 * @property string|null $address
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $tiktok
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_image',
        'description',
        'slug',
        'sub_brand',
        'coverage_area',
        'mobile_equipment',
        'transport_fee',
        'max_distance',
        'is_active',
        'address',
        'instagram',
        'facebook',
        'tiktok'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'coverage_area' => 'array',
        'mobile_equipment' => 'array',
        'is_active' => 'boolean',
        'transport_fee' => 'decimal:2',
        'max_distance' => 'integer'
    ];

    // Relatii
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'specialist_id');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'specialist_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class);
    }

    // Metode helper
    public function isSpecialist()
    {
        return $this->role === 'specialist';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function getSubBrandLabelAttribute()
    {
        $labels = [
            'dariaNails' => 'dariaNails - Manichiura & Pedichiura',
            'dariaHair' => 'dariaHair - Coafura & Styling', 
            'dariaGlow' => 'dariaGlow - Skincare & Makeup'
        ];
        
        return $labels[$this->sub_brand] ?? $this->sub_brand;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}