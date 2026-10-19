<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MediaResource;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MediaResourcePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MediaResource');
    }

    public function view(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('View:MediaResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MediaResource');
    }

    public function update(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('Update:MediaResource');
    }

    public function delete(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('Delete:MediaResource');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MediaResource');
    }

    public function restore(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('Restore:MediaResource');
    }

    public function forceDelete(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('ForceDelete:MediaResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MediaResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MediaResource');
    }

    public function replicate(AuthUser $authUser, MediaResource $mediaResource): bool
    {
        return $authUser->can('Replicate:MediaResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MediaResource');
    }
}
