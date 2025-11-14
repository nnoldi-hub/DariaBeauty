<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $duration
 * @property string $sub_brand
 * @property bool $is_mobile
 * @property array|null $equipment_needed
 * @property int|null $preparation_time
 * @property bool $is_active
 * @property string|null $image
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'duration', // in minute
        'sub_brand', // dariaNails, dariaHair, dariaGlow
        'is_mobile', // se poate face la domiciliu
        'equipment_needed', // echipamente necesare
        'preparation_time', // timp de pregatire
        'is_active',
        'image',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'preparation_time' => 'integer',
        'is_mobile' => 'boolean',
        'is_active' => 'boolean',
        'equipment_needed' => 'array'
    ];

    // Relatii
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySubBrand($query, $subBrand)
    {
        return $query->where('sub_brand', $subBrand);
    }

    public function scopeMobile($query)
    {
        return $query->where('is_mobile', true);
    }

    // Metode helper
    public function getFormattedPriceAttribute()
    {
        return number_format((float)$this->price, 0, ',', '.') . ' lei';
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
        }
        
        return $minutes . ' minute';
    }

    public function formatOf($field)
    {
        switch ($field) {
            case 'price':
                return $this->formatted_price;
            case 'duration':
                return $this->formatted_duration;
            default:
                return $this->$field;
        }
    }

    public function getSubBrandColorAttribute()
    {
        $colors = [
            'dariaNails' => '#E91E63', // Pink
            'dariaHair' => '#9C27B0',  // Purple
            'dariaGlow' => '#FF9800'   // Orange
        ];
        
        return $colors[$this->sub_brand] ?? '#6366F1';
    }
}