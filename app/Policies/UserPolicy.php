<?php

namespace App\Policies;

use App\Models\User;

/**
 * Ported from the last-admin-lockout guard in `Codemoi\Controller\Admin\UserController`
 * (legacy `src/Controller/Admin/UserController.php::update()`/`delete()`).
 * `requireAdmin()`-equivalent auth already happens at the route middleware
 * level — this only covers the one additional rule: demoting or deleting
 * the sole remaining role=1 account would lock everyone out of `/admin`
 * with no recovery path.
 */
class UserPolicy
{
    public function demote(User $actor, User $target, int $newRole): bool
    {
        if ((int) $target->role !== 1 || $newRole === 1) {
            return true;
        }

        return User::admins()->count() > 1;
    }

    public function delete(User $actor, User $target): bool
    {
        if ((int) $target->role !== 1) {
            return true;
        }

        return User::admins()->count() > 1;
    }
}
