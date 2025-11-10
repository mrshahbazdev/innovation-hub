<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('viewAny Team');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Team');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('update Team');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('delete Team');
    }
}
