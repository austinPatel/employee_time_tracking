<?php

namespace App\Policies;

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeLogPolicy
{

    /**
     * Determine whether the user employee & manager can view the model.
     */
    public function view(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id || $user->isManager();
    }

    /**
     * Determine whether the user employee & manager can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEmployee() || $user->isManager();

    }

    /**
     * Determine whether the user manager can update the model.
     */
    public function update(User $user, TimeLog $timeLog): bool
    {
        return $user->isManager();

    }

    /**
     * Determine whether the user manager can delete the model.
     */
    public function delete(User $user, TimeLog $timeLog): bool
    {
        return $user->isManager();
    }
}
