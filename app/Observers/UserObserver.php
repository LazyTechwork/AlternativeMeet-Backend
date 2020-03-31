<?php

namespace App\Observers;

use App\GlobalParams;
use App\Log;
use App\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function created(User $user)
    {
        Log::register($user, GlobalParams::ACTION_USER_CREATE, 'Создан пользователь');
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
