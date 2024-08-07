<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdeaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Idea $idea)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Idea $idea)
    {
        return $user->id === (int) $idea->user_id
            && $idea->created_at <= now()->subHours(6);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Idea $idea)
    {
        // dd('hello');
        return $user->id === (int) $idea->user_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Idea $idea)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Idea $idea)
    {
        //
    }
}