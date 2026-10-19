<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PressRelease;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PressReleasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PressRelease');
    }

    public function view(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('View:PressRelease');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PressRelease');
    }

    public function update(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('Update:PressRelease');
    }

    public function delete(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('Delete:PressRelease');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PressRelease');
    }

    public function restore(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('Restore:PressRelease');
    }

    public function forceDelete(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('ForceDelete:PressRelease');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PressRelease');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PressRelease');
    }

    public function replicate(AuthUser $authUser, PressRelease $pressRelease): bool
    {
        return $authUser->can('Replicate:PressRelease');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PressRelease');
    }
}
