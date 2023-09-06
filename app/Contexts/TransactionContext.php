<?php

namespace App\Contexts;

class TransactionContext
{
    public function __construct(
        public readonly int $payerId,
        public readonly int $receiverId,
        public readonly string $type,
        public readonly float $sum,
        public readonly ?string $purpose = null,
        public readonly ?string $errorMessage = null,
        public readonly ?int $provUserId = null,
        public readonly ?int $creatorId = null
    ) {
    }
}
