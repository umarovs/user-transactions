<?php

namespace App\Enums\Transactions;

enum TypeEnum: string
{
    case CREDIT = "credit";

    case DEBIT = "debit";
}
