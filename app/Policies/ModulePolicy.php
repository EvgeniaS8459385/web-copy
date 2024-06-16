<?php

namespace App\Policies;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Module $module): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Module $module): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Module $module): bool
    {
        return $user->isAdmin();
    }
}
