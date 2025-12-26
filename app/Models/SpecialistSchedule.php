<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model pentru programul de lucru al specialiștilor
 * 
 * @property int $id
 * @property int $specialist_id
 * @property int $day_of_week
 * @property bool $available_at_salon
 * @property string|null $salon_start_time
 * @property string|null $salon_end_time
 * @property bool $available_at_home
 * @property string|null $home_start_time
 * @property string|null $home_end_time
 * @property string|null $break_start_time
 * @property string|null $break_end_time
 * @property string|null $notes
 */
class SpecialistSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialist_id',
        'day_of_week',
        'available_at_salon',
        'salon_start_time',
        'salon_end_time',
        'available_at_home',
        'home_start_time',
        'home_end_time',
        'break_start_time',
        'break_end_time',
        'notes'
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'available_at_salon' => 'boolean',
        'available_at_home' => 'boolean',
    ];

    /**
     * Relație cu specialistul
     */
    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    /**
     * Denumirea zilei în română
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            0 => 'Duminică',
            1 => 'Luni',
            2 => 'Marți',
            3 => 'Miercuri',
            4 => 'Joi',
            5 => 'Vineri',
            6 => 'Sâmbătă'
        ];
        
        return $days[$this->day_of_week] ?? '';
    }

    /**
     * Denumirea scurtă a zilei
     */
    public function getDayShortNameAttribute(): string
    {
        $days = [
            0 => 'Dum',
            1 => 'Lun',
            2 => 'Mar',
            3 => 'Mie',
            4 => 'Joi',
            5 => 'Vin',
            6 => 'Sâm'
        ];
        
        return $days[$this->day_of_week] ?? '';
    }

    /**
     * Verifică dacă specialistul lucrează în această zi
     */
    public function isWorkingDay(): bool
    {
        return $this->available_at_salon || $this->available_at_home;
    }

    /**
     * Verifică dacă o anumită oră este în timpul pauzei
     */
    public function isInBreak($time): bool
    {
        if (!$this->break_start_time || !$this->break_end_time) {
            return false;
        }

        $checkTime = \Carbon\Carbon::parse($time)->format('H:i');
        $breakStart = \Carbon\Carbon::parse($this->break_start_time)->format('H:i');
        $breakEnd = \Carbon\Carbon::parse($this->break_end_time)->format('H:i');

        return $checkTime >= $breakStart && $checkTime < $breakEnd;
    }

    /**
     * Obține orele de lucru pentru un tip de locație
     */
    public function getWorkingHours(string $locationType = 'salon'): ?array
    {
        if ($locationType === 'salon' && $this->available_at_salon) {
            return [
                'start' => $this->salon_start_time,
                'end' => $this->salon_end_time
            ];
        }
        
        if ($locationType === 'home' && $this->available_at_home) {
            return [
                'start' => $this->home_start_time,
                'end' => $this->home_end_time
            ];
        }
        
        return null;
    }

    /**
     * Static helper pentru a obține zilele săptămânii
     */
    public static function getDaysOfWeek(): array
    {
        return [
            1 => 'Luni',
            2 => 'Marți',
            3 => 'Miercuri',
            4 => 'Joi',
            5 => 'Vineri',
            6 => 'Sâmbătă',
            0 => 'Duminică'
        ];
    }
}
