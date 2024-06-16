<?php

namespace App\Policies;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\User;

class ModulePartPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ModulePart $part): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ModulePart $part): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ModulePart $part): bool
    {
        return $user->isAdmin();
    }
}
