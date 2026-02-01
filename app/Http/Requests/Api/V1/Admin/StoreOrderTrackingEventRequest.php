<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Domain\Orders\TrackingStatusKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderTrackingEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $keys = array_map(fn ($c) => $c->value, TrackingStatusKey::cases());

        return [
            'status_key' => ['required', 'string', Rule::in($keys)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
