<?php

namespace App\Broadcasting;

use App\Models\System\User;

class PhoneCallChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  User  $user
     * @return array|bool
     */
    public function join(User $user, int $filialId)
    {
        return true;
    }
}
