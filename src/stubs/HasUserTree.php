<?php

namespace App\traits;

use App\Models\OrganizationSubadmin;
use App\Models\ReferentCollaborator;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

trait HasUserTree
{
    public function getUserTree($userIds, $users = [])
    {
        $userIds = User::whereIn('created_by', $userIds)->pluck('id')->all();

        if (! empty($userIds)) {
            $users = array_merge($users, $userIds);
            $users = $this->getUserTree($userIds, $users);
        }

        return array_unique($users);
    }

    public function getParentTree(User $user, $parents = [])
    {
        $parent = User::find($user->created_by);

        if (! is_null($parent) && $parent->id >= 1) {
            $parents[] = $parent->id;
            $parents = $this->getParentTree($parent, $parents);
        }

        return array_unique($parents);
    }

    public function getUserIds()
    {
        //if (auth()->user()->hasPermission('with.sibling')) {
        $roles = auth()->user()->roles;
        $siblings = $this->getSiblings();
        /*foreach ($roles as $role) {
            $siblings = array_merge($siblings, $role->users()->where(
                'users.created_by',
                auth()->user()->created_by
            )->pluck('users.id')->all());
        }*/
        return Cache::remember('userTree_'.session()->getId(), 600, fn () => $this->getUserTree($siblings, $siblings));
        /* } else {
             return Cache::remember('userTree', 600, fn () => $this->getUserTree([auth()->id()], [auth()->id()]));
         }*/
    }

    public function getSiblings(): array
    {
        $siblings = [auth()->id()];
        // Check all sibling collaborators
        if (auth()->user()->subjects['subadmin'] == 1) {
            $sub_collaborators = OrganizationSubadmin::select('subadmin_id')
                ->where('organization_id', auth()->user()->organization()->id)
                ->pluck('subadmin_id')->toArray();
            $siblings = array_merge($siblings, $sub_collaborators);
        }

        if (auth()->user()->subjects['referent'] == 1) {
            $ref_collaborators = ReferentCollaborator::where('referent_id', auth()->id())
                ->select('collaborator_id')->pluck('collaborator_id')->toArray();
            $siblings = array_merge($siblings, $ref_collaborators);
        }

        if (auth()->user()->subjects['ref-collaborator'] == 1) {
            $referent = ReferentCollaborator::where('collaborator_id', auth()->id())->first();
            $ref_collaborators = ReferentCollaborator::where('referent_id', $referent->id)
                ->select('collaborator_id')->pluck('collaborator_id')->toArray();
            $siblings = array_merge($siblings, $ref_collaborators);
            array_push($siblings, $referent->referent_id);
        }

        return array_filter($siblings);
    }
}
