<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CaseStudy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CaseStudyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CaseStudy');
    }

    public function view(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('View:CaseStudy');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CaseStudy');
    }

    public function update(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('Update:CaseStudy');
    }

    public function delete(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('Delete:CaseStudy');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CaseStudy');
    }

    public function restore(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('Restore:CaseStudy');
    }

    public function forceDelete(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('ForceDelete:CaseStudy');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CaseStudy');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CaseStudy');
    }

    public function replicate(AuthUser $authUser, CaseStudy $caseStudy): bool
    {
        return $authUser->can('Replicate:CaseStudy');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CaseStudy');
    }
}
