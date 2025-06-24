<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function update(User $user): Response
    {
        return is_null($user->oauth_provider) && is_null($user->oauth_id)
            ? Response::allow()
            : Response::deny('Your account is linked to an OAuth provider.');
    }
}
