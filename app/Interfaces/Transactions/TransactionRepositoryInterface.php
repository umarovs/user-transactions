<?php

namespace App\Interfaces\Transactions;

use App\Contexts\TransactionContext;
use App\Models\Transactions\Transaction;

interface TransactionRepositoryInterface
{
    public function create(TransactionContext $context): Transaction;

    public function persist(Transaction $transaction): bool;
}
