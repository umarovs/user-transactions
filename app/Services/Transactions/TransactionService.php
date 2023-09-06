<?php

namespace App\Services\Transactions;

use App\Contexts\TransactionContext;
use App\Enums\Transactions\StatusEnum;
use App\Interfaces\Transactions\TransactionRepositoryInterface;
use App\Models\Transactions\Transaction;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function create(TransactionContext $context): Transaction
    {
        $transaction = new Transaction();
        $transaction->creator_id = $context->creatorId;
        $transaction->payer_id = $context->payerId;
        $transaction->receiver_id = $context->receiverId;
        $transaction->type = $context->type;
        $transaction->sum = $context->sum;
        $transaction->purpose = $context->purpose;
        $transaction->error_message = $context->errorMessage;
        $transaction->prov_user_id = $context->provUserId;
        $this->transactionRepository->persist($transaction);

        return $transaction->fresh();
    }

    public function setStatus(Transaction $transaction, StatusEnum $statusEnum): bool
    {
        $transaction->status = $statusEnum->value;

        return $this->transactionRepository->persist($transaction);
    }

    public function setFailed(Transaction $transaction, string $errorMessage): bool
    {
        $transaction->status = StatusEnum::FAILED->value;
        $transaction->error_message = $errorMessage;

        return $this->transactionRepository->persist($transaction);
    }

    public function setPaid(Transaction $transaction): bool
    {
        return $this->setStatus($transaction, StatusEnum::PAID);
    }
}
