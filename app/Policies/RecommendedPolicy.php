<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Recommended;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RecommendedPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Recommended');
    }

    public function view(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('View:Recommended');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Recommended');
    }

    public function update(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('Update:Recommended');
    }

    public function delete(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('Delete:Recommended');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Recommended');
    }

    public function restore(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('Restore:Recommended');
    }

    public function forceDelete(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('ForceDelete:Recommended');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Recommended');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Recommended');
    }

    public function replicate(AuthUser $authUser, Recommended $recommended): bool
    {
        return $authUser->can('Replicate:Recommended');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Recommended');
    }
}
