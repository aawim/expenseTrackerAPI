<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'category_id'       => 'sometimes|exists:categories,id',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'amount'            => 'sometimes|numeric|min:0.01',
            'description'       => 'nullable|string|max:255',
            'date'              => 'sometimes|date',
        ];
    }
}
