<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Ver se pode ver usuário
    public function view(User $authUser, User $user)
    {
        return $authUser->hasRole(['admin', 'director']) ||
               $authUser->id === $user->id;
    }

    // Ver se pode aprovar
    public function approve(User $authUser, User $user)
    {
        return $authUser->hasRole(['admin', 'director']) &&
               $user->isPending();
    }

    // Ver se pode rejeitar
    public function reject(User $authUser, User $user)
    {
        return $authUser->hasRole(['admin', 'director']) &&
               $user->isPending();
    }

    // Ver se pode suspender
    public function suspend(User $authUser, User $user)
    {
        return $authUser->hasRole(['admin', 'director']) &&
               !$user->hasRole(['admin', 'director']) &&
               $user->isApproved();
    }

    // Ver se pode editar
    public function update(User $authUser, User $user)
    {
        return $authUser->hasRole(['admin', 'director']) ||
               $authUser->id === $user->id;
    }

    // Ver se pode deletar
    public function delete(User $authUser, User $user)
    {
        return $authUser->hasRole('admin') &&
               !$user->hasRole('admin') &&
               $authUser->id !== $user->id;
    }

    // Ver se pode ver dashboard admin
    public function viewAdmin(User $user)
    {
        return $user->hasRole(['admin', 'director']);
    }
}
