<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseFormRequest;

class UpdateOrderRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:orders,id',
            'order_status_id' => 'required|integer|exists:order_statuses,id',
            'total_price' => 'required|numeric',
            'total_amount' => 'required|integer',
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
