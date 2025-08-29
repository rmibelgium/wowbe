<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SitePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Site $site): Response
    {
        return ! is_null($site->user_id) && (string) $user->id === (string) $site->user_id
            ? Response::allow()
            : Response::deny('You do not own this site.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Site $site): Response
    {
        return ! is_null($site->user_id) && (string) $user->id === (string) $site->user_id
            ? Response::allow()
            : Response::deny('You do not own this site.');
    }
}
