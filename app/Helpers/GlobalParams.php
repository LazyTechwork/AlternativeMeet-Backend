<?php


namespace App;


class GlobalParams
{
    /**
     * Minimum age
     */
    public const MIN_AGE = 7;

    /**
     * Maximum age
     */
    public const MAX_AGE = 87;

    /*
     * Earth radius (km)
     */
    public const EARTH_RADIUS = 6378;

    /*
     * Mathematical PI
     */
    public const PI = 3.141592;

    public const ACTION_USER_CREATE = 'user_create';
    public const ACTION_PHOTO_CHANGE = 'photo_change';
    public const ACTION_DESCRIPTION_CHANGE = 'description_change';

    public const DESCRIPTION_CHANGE_DELAY = 14; // Delay to change description in days
    public const AVATAR_CHANGE_DELAY = 7; // Delay to change avatar in days

}
