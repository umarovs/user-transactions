<?php

namespace App\Enums\Transactions;

enum StatusEnum: string
{
    case FORMED = "formed";

    case PAID = "paid";

    case CANCELED = "canceled";

    case FAILED = "failed";
}
