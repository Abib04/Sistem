<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'email',
        'expiry_date',
        'notes',
        'fcm_token'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Get days remaining until hosting expiry
     */
    public function getDaysRemainingAttribute()
    {
        return Carbon::now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Get status badge information
     */
    public function getStatusAttribute()
    {
        $days = $this->days_remaining;

        if ($days < 0) {
            return [
                'class' => 'danger',
                'text' => 'Kadaluarsa',
                'icon' => '🔴',
                'priority' => 4
            ];
        } elseif ($days <= 7) {
            return [
                'class' => 'warning',
                'text' => 'Mendesak',
                'icon' => '🟠',
                'priority' => 3
            ];
        } elseif ($days <= 30) {
            return [
                'class' => 'info',
                'text' => 'Perhatian',
                'icon' => '🟡',
                'priority' => 2
            ];
        } else {
            return [
                'class' => 'success',
                'text' => 'Aman',
                'icon' => '🟢',
                'priority' => 1
            ];
        }
    }

    /**
     * Check if hosting is expired
     */
    public function isExpired()
    {
        return $this->days_remaining < 0;
    }

    /**
     * Check if hosting expiry is urgent (within 7 days)
     */
    public function isUrgent()
    {
        return $this->days_remaining >= 0 && $this->days_remaining <= 7;
    }

    /**
     * Check if hosting needs attention (within 30 days)
     */
    public function needsAttention()
    {
        return $this->days_remaining > 7 && $this->days_remaining <= 30;
    }

    /**
     * Check if hosting is safe (more than 30 days)
     */
    public function isSafe()
    {
        return $this->days_remaining > 30;
    }
}
