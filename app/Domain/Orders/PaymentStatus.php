<?php

namespace App\Domain\Orders;

enum PaymentStatus: string
{
    case Initialized = 'initialized';
    case Success = 'success';
    case Failed = 'failed';
}
