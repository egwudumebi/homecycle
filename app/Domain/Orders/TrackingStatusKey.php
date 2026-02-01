<?php

namespace App\Domain\Orders;

enum TrackingStatusKey: string
{
    case OrderCreated = 'order_created';
    case PaymentConfirmed = 'payment_confirmed';

    case OrderProcessing = 'order_processing';
    case Packed = 'packed';

    case Shipped = 'shipped';
    case InTransit = 'in_transit';
    case OutForDelivery = 'out_for_delivery';

    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
