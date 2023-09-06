<?php

namespace App\Jobs\Transactions;

use App\Enums\Transactions\StatusEnum;
use App\Enums\Transactions\TypeEnum;
use App\Models\Transactions\Transaction;
use App\Models\Users\User;
use App\Services\Transactions\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProvTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->transactionService
            ->getItemsForProv()
            ->each(function ($transaction) {
                $transaction->

                DB::transaction(function () use ($transaction) {

                    try {
                        $status = StatusEnum::PROV;
                        $message = null;
                        $transactionSumma = $transaction->summa;

                        $user = $transaction->user;
                        $user->lockForUpdate();
                        $transaction->lockForUpdate();

                        $userBalance = floatval($user->balance);

                        if ($transaction->type === TypeEnum::DEBIT) {
                            if ($userBalance >= $transaction->summa) {
                                $transactionSumma *= -1;
                            } else {
                                $transactionSumma = 0;
                                $message = trans('transactions.out_of_debit') . " " . floatval($transaction->summa - floatval($user->balance));
                                $status = StatusEnum::FAILED;
                            }
                        }

                        $user->balance = floatval($userBalance + $transactionSumma);
                        $user->save();

                        $transaction->status = $status;
                        $transaction->error_message = $message;
                        $transaction->save();
                    } catch (QueryException $exception) {
                        $transaction->status = StatusEnum::FAILED;
                        $transaction->error_message = $exception->getMessage();
                        $transaction->save();
                    }

                }, 3);

            });
    }
}
