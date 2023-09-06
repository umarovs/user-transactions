<?php

namespace App\Models\Users;

use App\Models\Transactions\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'creator_id',
        'updater_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get user incremented transaction items
     *
     * @return HasMany
     */
    public function incrementTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver_id', 'id');
    }

    /**
     * Get user decremented transaction items
     *
     * @return HasMany
     */
    public function decrementTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id', 'id');
    }

    /**
     * Get user created transaction items
     *
     * @return HasMany
     */
    public function createdTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'creator_id', 'id');
    }
}
