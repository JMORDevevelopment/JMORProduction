<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RequestInformation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RequestInformationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RequestInformation');
    }

    public function view(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('View:RequestInformation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RequestInformation');
    }

    public function update(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('Update:RequestInformation');
    }

    public function delete(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('Delete:RequestInformation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RequestInformation');
    }

    public function restore(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('Restore:RequestInformation');
    }

    public function forceDelete(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('ForceDelete:RequestInformation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RequestInformation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RequestInformation');
    }

    public function replicate(AuthUser $authUser, RequestInformation $requestInformation): bool
    {
        return $authUser->can('Replicate:RequestInformation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RequestInformation');
    }
}
