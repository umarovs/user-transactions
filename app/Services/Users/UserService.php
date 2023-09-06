<?php

namespace App\Services\Users;

use App\Interfaces\Users\UserRepositoryInterface;
use App\Models\Users\User;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function getByIdOrFail(int $id): User
    {
        return $this->userRepository->getByIdOrFail($id);
    }

    public function lockForUpdate(int $id): User
    {
        return $this->userRepository->lockForUpdate($id);
    }

    public function incrementBalance(User $user, float $sum): bool
    {
        $user->balance += $sum;
        return $this->userRepository->persist($user);
    }

    public function decrementBalance(User $user, float $sum): bool
    {
        $user->balance -= $sum;
        return $this->userRepository->persist($user);
    }
}
