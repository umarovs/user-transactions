<?php

namespace App\Interfaces\Users;

use App\Models\Users\User;

interface UserRepositoryInterface
{
    public function getByIdOrFail(int $id): User;

    public function lockForUpdate(int $id): User;

    public function persist(User $user): bool;
}
