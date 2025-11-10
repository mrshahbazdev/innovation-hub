<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
        return $user->hasPermissionTo('viewAny User');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create User');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('update User');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('delete User');
    }
}
