<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'shop_name',
        'email',
        'password',
        'trial_ends_at',
        'subscription_active',
        'subscription_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'subscription_expires_at' => 'datetime',
            'subscription_active' => 'boolean',
        ];
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription_active) {
            return false;
        }

        // Agar expiry date set hai, check karo abhi tak expire nahi hui
        if ($this->subscription_expires_at) {
            return $this->subscription_expires_at->isFuture();
        }

        // Agar expiry date set nahi hai, matlab manually hamesha ke liye active hai
        return true;
    }

    public function hasAccess(): bool
    {
        return $this->onTrial() || $this->hasActiveSubscription();
    }

    public function trialDaysLeft(): int
    {
        if (!$this->trial_ends_at || $this->trial_ends_at->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->trial_ends_at);
    }
}
