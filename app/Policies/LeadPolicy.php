<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lead $lead): bool
    {
        if ($user->isOwner() || $user->isMarketing()) {
            return true;
        }

        // Developer only can view contacted leads
        return $lead->isContacted();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isMarketing();
    }

    /**
     * Determine whether the user can update the model (basic info, notes).
     */
    public function update(User $user, Lead $lead): bool
    {
        return $user->isOwner() || $user->isMarketing();
    }

    /**
     * Determine whether the user can edit lead status (e.g. Belum Dihubungi -> WA Terkirim -> Deal).
     */
    public function updateStatus(User $user, Lead $lead): bool
    {
        return $user->isOwner() || $user->isMarketing();
    }

    /**
     * Determine whether the user can update progress, tasks checklist, and photos.
     */
    public function updateProgress(User $user, Lead $lead): bool
    {
        return $user->isOwner() || $user->isDeveloper();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lead $lead): bool
    {
        return $user->isOwner();
    }
}
