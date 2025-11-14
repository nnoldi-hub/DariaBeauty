<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'url',
        'username',
        'is_active',
        'display_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Relatia cu userul (specialist)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pentru linkuri active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pentru o anumita platforma
     */
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Obtine icon-ul Font Awesome pentru platforma
     */
    public function getIconAttribute()
    {
        $icons = [
            'instagram' => 'fab fa-instagram',
            'facebook' => 'fab fa-facebook',
            'tiktok' => 'fab fa-tiktok',
            'youtube' => 'fab fa-youtube',
            'whatsapp' => 'fab fa-whatsapp',
            'twitter' => 'fab fa-twitter',
            'linkedin' => 'fab fa-linkedin',
            'pinterest' => 'fab fa-pinterest'
        ];

        return $icons[$this->platform] ?? 'fas fa-link';
    }

    /**
     * Obtine culoarea pentru platforma
     */
    public function getColorAttribute()
    {
        $colors = [
            'instagram' => '#E4405F',
            'facebook' => '#1877F2',
            'tiktok' => '#000000',
            'youtube' => '#FF0000',
            'whatsapp' => '#25D366',
            'twitter' => '#1DA1F2',
            'linkedin' => '#0A66C2',
            'pinterest' => '#E60023'
        ];

        return $colors[$this->platform] ?? '#333333';
    }

    /**
     * Formateaza URL-ul pentru WhatsApp
     */
    public function getFormattedUrlAttribute()
    {
        if ($this->platform === 'whatsapp' && !str_starts_with($this->url, 'http')) {
            // Converteste numarul de telefon in link WhatsApp
            $phone = preg_replace('/[^0-9+]/', '', $this->url);
            return 'https://wa.me/' . $phone;
        }

        return $this->url;
    }
}
