<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sub_category_id' => ['required', 'integer', 'exists:sub_categories,id'],
            'state_id' => ['required', 'integer', 'exists:location_states,id'],
            'city_id' => ['required', 'integer', 'exists:location_cities,id'],

            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],

            'seller_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'seller_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'whatsapp_phone' => ['sometimes', 'nullable', 'string', 'max:50'],

            'status' => ['sometimes', 'string', 'in:active,sold,hidden'],
            'is_featured' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
