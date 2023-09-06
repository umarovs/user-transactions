<?php

namespace App\Repositories\Users;

use App\Interfaces\Users\UserRepositoryInterface;
use App\Models\Users\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $user,
    ) {}

    public function getByIdOrFail(int $id): User
    {
        return $this->user->findOrFail($id);
    }

    public function lockForUpdate(int $id): User
    {
        return $this->user->lockForUpdate()->findOrFail($id);
    }

    public function persist(User $user): bool
    {
        return $user->save();
    }
}
