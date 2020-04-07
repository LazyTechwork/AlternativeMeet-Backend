<?php

namespace App;

use App\Helpers\GlobalUtils;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property int $id
 * @property int $vk_id
 * @property string $firstname
 * @property string $lastname
 * @property string|null $description
 * @property mixed|null $geo
 * @property string|null $birthday
 * @property mixed|null $social
 * @property string|null $avatar
 * @property int $sex
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVkId($value)
 * @mixin \Eloquent
 * @property-read mixed $age
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $sympathised
 * @property-read int|null $sympathised_count
 * @property string|null $photo Ссылка на фото профиля
 * @property int $agefrom Интересы: нижний возраст
 * @property int $ageto Интересы: верхний возраст
 * @property int $tag_id Интересы: тег
 * @property string $vk_token Хеш-токен
 * @property-read \App\Tag $tag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAgefrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAgeto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVkToken($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $dates = ['birthday'];

    /**
     * Returns all users that liked this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sympathised()
    {
        return $this->belongsToMany(User::class, 'sympathy', 'to', 'id')
            ->where('status', '>', 0);
    }

    /**
     * Get distance between one geo point and geo point of this user
     *
     * @param $geo
     * @return float|int
     */
    public function distance($geo)
    {
        return GlobalUtils::geoDistance($this->geo, $geo);
    }

    /**
     * Returns age
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return Carbon::now()->diffInYears($this->birthday);
    }

    /**
     * Relationship to Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Is all user fields filled
     *
     * @return bool
     */
    public function isRegistered()
    {
        return $this->firstname !== null && $this->lastname !== null &&
            $this->sex !== null && $this->birthday !== null &&
            $this->geo !== null && $this->agefrom !== null && $this->ageto !== null;
    }

    /**
     * Can user change description (due to delay)
     *
     * @return bool
     */
    public function canChangeDescription()
    {
        $log = Log::whereUserId($this->id)->whereActionType(GlobalParams::ACTION_DESCRIPTION_CHANGE)->latest()->first();
        if (!$log)
            return true;
        else if (Carbon::now()->diffInDays($log->created_at) >= GlobalParams::DESCRIPTION_CHANGE_DELAY)
            return true;
        else
            return false;
    }

    /**
     * Can user change avatar (due to delay)
     *
     * @return bool
     */
    public function canChangeAvatar()
    {
        $log = Log::whereUserId($this->id)->whereActionType(GlobalParams::ACTION_PHOTO_CHANGE)->latest()->first();
        if (!$log)
            return true;
        else if (Carbon::now()->diffInDays($log->created_at) >= GlobalParams::AVATAR_CHANGE_DELAY)
            return true;
        else
            return false;
    }


}
