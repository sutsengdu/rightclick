<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecordRequest extends FormRequest
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
            'seat' => 'nullable|string|max:255',
            'member_ID' => 'nullable|string|max:255',
            'member_amount' => 'nullable|numeric|min:0',
            'order' => 'nullable|string',
            'order_amount' => 'nullable|numeric|min:0',
            'total' => 'sometimes|required|numeric|min:0',
            'paid' => 'sometimes|required|boolean',
            'online' => 'sometimes|required|boolean',
            'debt' => 'nullable|numeric|min:0',
        ];
    }
}
