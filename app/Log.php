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
 * @property int $id
 * @property int $user_id Пользователь
 * @property string $action_type Тип действия
 * @property string $description Описание действия
 * @property mixed $user_state Текущее состояние пользователя
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereActionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUserState($value)
 */
class Log extends Model
{
    protected $guarded = [];

    public static function register(User $user, string $action_type, string $description)
    {
        $user = $user->fresh();
        Log::create([
            'user_id' => $user->id,
            'action_type' => $action_type,
            'description' => $description,
            'user_state' => $user->toArray()
        ]);
    }
}
