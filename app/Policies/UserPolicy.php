<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    private function canManageUsers(User $actor): bool
    {
        if ($actor->status !== 'active') {
            return false;
        }

        $roles = $actor->roles->pluck('name');

        return $roles->contains('super_admin')
            || $roles->contains('admin')
            || ($roles->isEmpty() && in_array($actor->role, ['super_admin', 'admin'], true));
    }

    public function viewAny(User $actor): bool
    {
        return $this->canManageUsers($actor);
    }

    public function view(User $actor, User $record): bool
    {
        return $this->canManageUsers($actor);
    }

    public function create(User $actor): bool
    {
        return $this->canManageUsers($actor);
    }

    public function update(User $actor, User $record): bool
    {
        return $this->canManageUsers($actor);
    }

    public function delete(User $actor, User $record): bool
    {
        return $this->canManageUsers($actor);
    }

    public function deleteAny(User $actor): bool
    {
        return $this->canManageUsers($actor);
    }
}
