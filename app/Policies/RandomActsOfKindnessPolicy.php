<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RandomActsOfKindness;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RandomActsOfKindnessPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RandomActsOfKindness');
    }

    public function view(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('View:RandomActsOfKindness');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RandomActsOfKindness');
    }

    public function update(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('Update:RandomActsOfKindness');
    }

    public function delete(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('Delete:RandomActsOfKindness');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RandomActsOfKindness');
    }

    public function restore(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('Restore:RandomActsOfKindness');
    }

    public function forceDelete(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('ForceDelete:RandomActsOfKindness');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RandomActsOfKindness');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RandomActsOfKindness');
    }

    public function replicate(AuthUser $authUser, RandomActsOfKindness $randomActsOfKindness): bool
    {
        return $authUser->can('Replicate:RandomActsOfKindness');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RandomActsOfKindness');
    }
}
