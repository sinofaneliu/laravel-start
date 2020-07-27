<?php

namespace Sinofaneliu\LaravelStart;

use Sinofaneliu\LaravelStart\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, $ability)
    {
        return null;
    }
}
