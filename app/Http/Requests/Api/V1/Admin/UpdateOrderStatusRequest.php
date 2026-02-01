<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Domain\Orders\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowed = [
            OrderStatus::Pending->value,
            OrderStatus::Paid->value,
            OrderStatus::Processing->value,
            OrderStatus::Shipped->value,
            OrderStatus::Delivered->value,
            OrderStatus::Cancelled->value,
        ];

        return [
            'status' => ['required', 'string', Rule::in($allowed)],
        ];
    }
}
