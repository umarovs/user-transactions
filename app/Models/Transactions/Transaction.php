<?php

namespace App\Models\Transactions;

use App\Enums\Transactions\StatusEnum;
use App\Models\Users\User;
use App\Traits\UserStamps\CreatorAndUpdater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use CreatorAndUpdater;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'updater_id',
        'payer_id',
        'receiver_id',
        'type',
        'sum',
        'purpose',
        'status',
        'prov_user_id',
        'error_message'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($item) {
            $item->status = StatusEnum::FORMED->value;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get payer relation model instance
     *
     * @return BelongsTo
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }

    /**
     * Get receiver relation model instance
     *
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    /**
     * Get prov user relation model instance
     *
     * @return BelongsTo
     */
    public function prov_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prov_user_id', 'id');
    }
}
