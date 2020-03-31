<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Log
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log query()
 * @mixin \Eloquent
 */
class Log extends Model
{
    protected $guarded = [];

    public static function register(User $user, string $action_type, string $description)
    {
        Log::create([
            'user_id' => $user->id,
            'action_type' => $action_type,
            'description' => $description,
            'user_state' => $user->toArray()
        ]);
    }
}
