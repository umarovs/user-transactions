<?php

namespace App\Console\Transactions;

use App\Contexts\TransactionContext;
use App\Enums\Transactions\TypeEnum;
use App\Services\Billings\BillingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class TransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создания транзакции';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected BillingService $billingService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->billingService->payment($this->getContext());

        $this->info("Транзакция успешно добавлена");

        return 0;
    }

    protected function getContext(): TransactionContext
    {
        $type = $this->choice('Какую транзакию хотите создать?', array_column(TypeEnum::cases(), 'name', 'value'));
        $payerId = $this->getPayerId();
        $receiverId = $this->getReceiverId();
        $sum = $this->getSum();
        $creatorId = $this->getCreatorId();
        $purpose = $this->ask('Укажите назначения платежа (опционально):');

        return new TransactionContext(
            payerId: $payerId,
            receiverId: $receiverId,
            type: $type,
            sum: $sum,
            purpose: $purpose,
            creatorId: $creatorId
        );
    }

    protected function getPayerId(): ?int
    {
        $payerId = null;
        $skipFlag = false;

        while ($skipFlag === false) {
            $skipFlag = true;
            $payerId = (int)$this->ask('Укажите ID плательщика:');
            if (! $this->validateUser($payerId)) {
                $skipFlag = false;
                $this->error("Плательщик по данному ID не найден в БД!");
            }
        }

        return $payerId;
    }

    protected function getReceiverId(): ?int
    {
        $receiverId = null;
        $skipFlag = false;

        while ($skipFlag === false) {
            $skipFlag = true;
            $receiverId = (int)$this->ask('Укажите ID получателя:');
            if (! $this->validateUser($receiverId)) {
                $skipFlag = false;
                $this->error("Получатель по данному ID не найден в БД!");
            }
        }

        return $receiverId;
    }

    protected function getSum(): ?float
    {
        $sum = null;
        $skipFlag = false;
        while ($skipFlag === false) {
            $skipFlag = true;
            $sum = floatval($this->ask('Укажите сумму транзакцию:'));
            if ($sum < PHP_FLOAT_EPSILON) {
                $skipFlag = false;
                $this->error("Значение должно быть целое/дробное число!");
            }
        }

        return $sum;
    }

    public function getCreatorId(): ?int
    {
        $creatorId = null;
        $skipFlag = false;

        while ($skipFlag === false) {
            $skipFlag = true;
            $creatorId = (int)$this->ask('Укажите ID создателя транзакции (опционально):');
            if ($creatorId > 0) {
                if (! $this->validateUser($creatorId)) {
                    $skipFlag = false;
                    $this->error("Пользователь по данному ID не найден в БД!");
                }
            }
        }

        return $creatorId;
    }

    protected function validateUser(int $id): bool
    {
        $validator = Validator::make([
            'user_id' => $id
        ], ['user_id' => 'exists:users,id']);

        return $validator->passes();
    }
}
