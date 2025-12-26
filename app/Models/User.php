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
        'tiktok',
        'offers_at_salon',
        'offers_at_home',
        'salon_address',
        'salon_lat',
        'salon_lng',
        'salon_id',
        'salon_specialists_count',
        'is_salon_owner',
        'salon_logo',
        'salon_description'
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
        'is_salon_owner' => 'boolean',
        'transport_fee' => 'decimal:2',
        'max_distance' => 'integer',
        'offers_at_salon' => 'boolean',
        'offers_at_home' => 'boolean',
        'salon_lat' => 'decimal:8',
        'salon_lng' => 'decimal:8',
        'salon_specialists_count' => 'integer'
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

    /**
     * Programul de lucru al specialistului
     */
    public function schedules()
    {
        return $this->hasMany(SpecialistSchedule::class, 'specialist_id')->orderBy('day_of_week');
    }

    /**
     * Obține programul pentru o zi specifică
     */
    public function getScheduleForDay(int $dayOfWeek): ?SpecialistSchedule
    {
        return $this->schedules()->where('day_of_week', $dayOfWeek)->first();
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
    
    /**
     * Accessor pentru sub_brands (decode JSON la array)
     */
    public function getSubBrandsAttribute()
    {
        if (empty($this->sub_brand)) {
            return [];
        }
        
        // Dacă e deja array (din json_decode automat)
        if (is_array($this->sub_brand)) {
            return $this->sub_brand;
        }
        
        // Încearcă să decodeze JSON
        $decoded = json_decode($this->sub_brand, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // Fallback: returnează ca array cu un singur element
        return [$this->sub_brand];
    }
    
    /**
     * Returnează label-urile pentru toate sub_brands
     */
    public function getSubBrandsLabelsAttribute()
    {
        $labels = [
            'dariaNails' => 'Manichiură & Pedichiură',
            'dariaHair' => 'Coafură & Styling', 
            'dariaGlow' => 'Skincare & Makeup'
        ];
        
        $brands = $this->sub_brands;
        return array_map(function($brand) use ($labels) {
            return $labels[$brand] ?? $brand;
        }, $brands);
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