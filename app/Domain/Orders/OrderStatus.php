<?php

namespace App\Domain\Orders;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    // Future tracking statuses (shipping lifecycle)
    case Shipped = 'shipped';
    case Delivered = 'delivered';
}
