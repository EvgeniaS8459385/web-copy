<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAnyTeacher(User $user): bool
    {
        return $user->isAdmin();
    }

    public function viewAnyStudent(User $user): bool
    {
        return $user->isAdmin();
    }

    public function viewAnyAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    public function viewTeacher(User $user, User $teacher): bool
    {
        if (!$teacher->isTeacher()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function viewStudent(User $user, User $student): bool
    {
        if (!$student->isStudent()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function viewAdmin(User $user, User $admin): bool
    {
        if (!$admin->isAdmin()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function createTeacher(User $user): bool
    {
        return $user->isAdmin();
    }

    public function createStudent(User $user): bool
    {
        return $user->isAdmin();
    }

    public function createAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    public function updateTeacher(User $user, User $teacher): bool
    {
        if (!$teacher->isTeacher()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function updateStudent(User $user, User $student): bool
    {
        if (!$student->isStudent()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function updateAdmin(User $user, User $admin): bool
    {
        if (!$admin->isAdmin()) {
            return false;
        }
        return $user->isAdmin() || $user->id === $admin->id;
    }

    public function deleteTeacher(User $user, User $teacher): bool
    {
        if (!$teacher->isTeacher()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function deleteStudent(User $user, User $student): bool
    {
        if (!$student->isStudent()) {
            return false;
        }
        return $user->isAdmin();
    }

    public function deleteAdmin(User $user, User $admin): bool
    {
        if (!$admin->isAdmin()) {
            return false;
        }
        return $user->isAdmin();
    }
}
