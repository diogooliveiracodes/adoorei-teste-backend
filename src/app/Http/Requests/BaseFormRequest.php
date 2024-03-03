<?php

namespace App\Http\Requests;

use App\Models\Log;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


abstract class BaseFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        Log::create([
            'level' => 'error',
            'message' => 'Validation failed',
            'context' => json_encode($validator->errors()),
            'client_ip' => request()->ip(),
            'user_id' => auth()->user() ?? null,
            'created_at' => now(),
        ]);

        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
        ],  Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();
}
