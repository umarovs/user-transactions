<?php

namespace App\Traits\UserStamps;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait Creator
{
    /**
     * Bootstrap creator trait.
     *
     * @return void
     */
    public static function bootCreator(): void
    {
        static::creating(function ($item) {
            if (empty($item->creator_id)) {
                $item->creator_id = Auth::user()?->id;
            }
        });
    }

    /**
     * Get creator relation model instance
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
