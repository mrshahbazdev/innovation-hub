<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    /**
     * Super Admin ko tamam access de dein
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) {
            return true;
        }
        return null; // Baaqi rules ko check karne dein
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('viewAny Idea');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Idea');
    }

    public function update(User $user, Idea $idea): bool
    {
        return $user->hasPermissionTo('update Idea');
    }

    public function delete(User $user, Idea $idea): bool
    {
        return $user->hasPermissionTo('delete Idea');
    }
}
