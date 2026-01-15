<?php

namespace App\Policies;

use App\Models\ChatThread;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatThreadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChatThread $thread): bool
    {
        return $thread->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('teacher');
    }

    public function message(User $user, ChatThread $thread)
    {
        return $thread->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatThread $chatThread): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatThread $chatThread): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatThread $chatThread): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatThread $chatThread): bool
    {
        return false;
    }
}
