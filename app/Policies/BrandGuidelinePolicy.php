<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BrandGuideline;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BrandGuidelinePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BrandGuideline');
    }

    public function view(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('View:BrandGuideline');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BrandGuideline');
    }

    public function update(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('Update:BrandGuideline');
    }

    public function delete(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('Delete:BrandGuideline');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BrandGuideline');
    }

    public function restore(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('Restore:BrandGuideline');
    }

    public function forceDelete(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('ForceDelete:BrandGuideline');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BrandGuideline');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BrandGuideline');
    }

    public function replicate(AuthUser $authUser, BrandGuideline $brandGuideline): bool
    {
        return $authUser->can('Replicate:BrandGuideline');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BrandGuideline');
    }
}
