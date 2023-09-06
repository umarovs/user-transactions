<?php

namespace App\Repositories\Transactions;

use App\Contexts\TransactionContext;
use App\Enums\Transactions\StatusEnum;
use App\Interfaces\Transactions\TransactionRepositoryInterface;
use App\Models\Transactions\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        protected Transaction $transaction
    ) {}

    public function create(TransactionContext $context): Transaction
    {
        $this->transaction->creator_id = $context->creatorId;
        $this->transaction->payer_id = $context->payerId;
        $this->transaction->receiver_id = $context->receiverId;
        $this->transaction->type = $context->type;
        $this->transaction->sum = $context->sum;
        $this->transaction->purpose = $context->purpose;
        $this->transaction->error_message = $context->errorMessage;
        $this->transaction->prov_user_id = $context->provUserId;
        $this->transaction->save();

        return $this->transaction->fresh();
    }

    public function persist(Transaction $transaction): bool
    {
        return $transaction->save();
    }
}
