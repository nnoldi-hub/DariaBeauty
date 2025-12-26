<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int $specialist_id
 * @property string|null $client_name
 * @property string|null $client_email
 * @property string|null $client_phone
 * @property int $service_id
 * @property \Illuminate\Support\Carbon $appointment_date
 * @property string $appointment_time
 * @property string $status
 * @property string|null $notes
 * @property bool $is_home_service
 * @property string|null $client_address
 * @property string|null $client_city
 * @property string|null $client_zone
 * @property float|null $distance_km
 * @property float|null $transport_fee
 * @property int|null $estimated_travel_time
 * @property string|null $special_instructions
 * @property string|null $payment_status
 * @property string|null $payment_method
 * @property float|null $total_amount
 * @property string|null $review_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialist_id',
        'client_name',
        'client_email',
        'client_phone',
        'service_id',
        'appointment_date',
        'appointment_time',
        'duration', // durata în minute (copiată de la serviciu)
        'status', // pending, confirmed, completed, cancelled
        'completed_at',
        'notes',
        // Campuri pentru servicii la domiciliu
        'is_home_service',
        'client_address',
        'client_city',
        'client_zone',
        'distance_km',
        'transport_fee',
        'estimated_travel_time',
        'special_instructions',
        'payment_status', // pending, paid, refunded
        'payment_method',
        'total_amount',
        'review_token' // pentru link recenzie
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'duration' => 'integer', // durata în minute
        'completed_at' => 'datetime',
        'is_home_service' => 'boolean',
        'distance_km' => 'decimal:2',
        'transport_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'estimated_travel_time' => 'integer' // in minute
    ];

    // Relatii
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function smsLogs()
    {
        return $this->hasMany(\App\Models\SmsLog::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', Carbon::today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', Carbon::today())
                    ->where('status', '!=', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHomeService($query)
    {
        return $query->where('is_home_service', true);
    }

    // Metode helper
    public function getFullDateTimeAttribute()
    {
        $date = $this->appointment_date instanceof Carbon ? 
                $this->appointment_date->format('Y-m-d') : 
                Carbon::parse($this->appointment_date)->format('Y-m-d');
        
        return Carbon::parse($date . ' ' . $this->appointment_time);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'In asteptare',
            'confirmed' => 'Confirmata',
            'completed' => 'Finalizata',
            'cancelled' => 'Anulata'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => '#FBA311',    // Yellow
            'confirmed' => '#06D6A0',  // Green
            'completed' => '#118AB2',  // Blue
            'cancelled' => '#F72585'   // Red
        ];
        
        return $colors[$this->status] ?? '#6B7280';
    }

    public function getTotalWithTransportAttribute()
    {
        $servicePrice = $this->service ? $this->service->price : 0;
        return $servicePrice + ($this->transport_fee ?? 0);
    }

    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->client_address,
            $this->client_zone,
            $this->client_city
        ]);
        
        return implode(', ', $parts);
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && 
               $this->review_token && 
               !$this->review;
    }

    public function generateReviewToken()
    {
        $this->review_token = bin2hex(random_bytes(32));
        $this->save();
        
        return $this->review_token;
    }
}