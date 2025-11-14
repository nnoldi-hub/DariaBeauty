<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $image_path
 * @property string|null $caption
 * @property string $sub_brand
 * @property string $before_after
 * @property bool $is_featured
 * @property int|null $service_id
 * @property array|null $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'user_id',
        'image_path',
        'caption',
        'sub_brand', // dariaNails, dariaHair, dariaGlow
        'before_after', // before/after/single
        'is_featured',
        'service_id', // optional - legat de un serviciu specific
        'tags' // JSON array cu taguri
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'tags' => 'array'
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

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBySubBrand($query, $subBrand)
    {
        return $query->where('sub_brand', $subBrand);
    }

    public function scopeBeforeAfter($query)
    {
        return $query->whereIn('before_after', ['before', 'after']);
    }

    // Metode helper
    public function getImageUrlAttribute()
    {
        return asset('storage/gallery/' . $this->image_path);
    }

    public function getThumbUrlAttribute()
    {
        $pathInfo = pathinfo($this->image_path);
        $thumbPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];
        
        return asset('storage/gallery/' . $thumbPath);
    }
}

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform', // instagram, facebook, tiktok, youtube, whatsapp
        'url',
        'username',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relatii
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Metode helper
    public function getPlatformIconAttribute()
    {
        $icons = [
            'instagram' => 'fab fa-instagram',
            'facebook' => 'fab fa-facebook',
            'tiktok' => 'fab fa-tiktok',
            'youtube' => 'fab fa-youtube',
            'whatsapp' => 'fab fa-whatsapp'
        ];
        
        return $icons[$this->platform] ?? 'fas fa-link';
    }

    public function getPlatformColorAttribute()
    {
        $colors = [
            'instagram' => '#E4405F',
            'facebook' => '#1877F2',
            'tiktok' => '#000000',
            'youtube' => '#FF0000',
            'whatsapp' => '#25D366'
        ];
        
        return $colors[$this->platform] ?? '#6B7280';
    }
}