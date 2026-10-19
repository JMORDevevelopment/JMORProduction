<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MediaVideo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MediaVideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MediaVideo');
    }

    public function view(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('View:MediaVideo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MediaVideo');
    }

    public function update(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('Update:MediaVideo');
    }

    public function delete(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('Delete:MediaVideo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MediaVideo');
    }

    public function restore(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('Restore:MediaVideo');
    }

    public function forceDelete(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('ForceDelete:MediaVideo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MediaVideo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MediaVideo');
    }

    public function replicate(AuthUser $authUser, MediaVideo $mediaVideo): bool
    {
        return $authUser->can('Replicate:MediaVideo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MediaVideo');
    }
}
