<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
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
        return $user->hasPermissionTo('viewAny Role');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update Role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete Role');
    }
}
