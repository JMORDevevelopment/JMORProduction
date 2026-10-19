<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MediaInquiry;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MediaInquiryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MediaInquiry');
    }

    public function view(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('View:MediaInquiry');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MediaInquiry');
    }

    public function update(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('Update:MediaInquiry');
    }

    public function delete(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('Delete:MediaInquiry');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MediaInquiry');
    }

    public function restore(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('Restore:MediaInquiry');
    }

    public function forceDelete(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('ForceDelete:MediaInquiry');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MediaInquiry');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MediaInquiry');
    }

    public function replicate(AuthUser $authUser, MediaInquiry $mediaInquiry): bool
    {
        return $authUser->can('Replicate:MediaInquiry');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MediaInquiry');
    }
}
