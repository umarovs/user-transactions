<?php

namespace App\Services\Billings;

use App\Contexts\TransactionContext;
use App\Enums\Transactions\StatusEnum;
use App\Services\Transactions\TransactionService;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function __construct(
        protected TransactionService $transactionService,
        protected UserService $userService
    ) {}

    public function payment(TransactionContext $context): void
    {
        $transaction = $this->transactionService->create($context);

        try {
            DB::transaction(function () use ($transaction, $context) {

                $payer = $this->userService->lockForUpdate($context->payerId);

                if ($payer->balance < $context->sum) {
                    $this->transactionService->setFailed($transaction, "Insufficient funds in the balance");
                    return;
                }

                $receiver = $this->userService->lockForUpdate($context->receiverId);

                $this->userService->decrementBalance($payer, $context->sum);
                $this->userService->incrementBalance($receiver, $context->sum);

                $this->transactionService->setStatus($transaction, StatusEnum::PAID);

            }, 3);
        } catch (\Throwable $exception) {
            Log::error("Transaction execution error", [
                "transactionID" => $transaction->id,
                "errorMessage" => $exception->getMessage()
            ]);
            $this->transactionService->setFailed($transaction, "Transaction execution error");
        }
    }
}
