<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Status;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * 删除微博的策略
     * @param User   $user
     * @param Status $status
     * @return bool
     */
    public function destroy(User $user, Status $status)
    {
        // 删除微博的时候是当前用户自身的微博
        return $user->id === $status->user_id;
    }
}
