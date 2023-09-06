<?php

namespace App\Traits\UserStamps;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait Updater
{
    /**
     * Bootstrap updater trait.
     *
     * @return void
     */
    public static function bootUpdater(): void
    {
        static::updating(function ($item) {
            if (Auth::user()) {
                $item->updater_id = Auth::user()->id;
            }
        });
    }

    /**
     * Get updater relation model instance
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id', 'id');
    }
}
