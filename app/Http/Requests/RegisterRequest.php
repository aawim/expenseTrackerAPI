<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            // 'name'     => 'required|string|max:255',

            'name'               => [
                'required',
                'string',
                'min:5',
                'max:12',
                'regex:/^[a-zA-Z0-9]+$/',
            ],

            'email'              => 'required|string|email|max:255|unique:users',
            'password'           => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/',
            ],
            'password.confirmed' => 'The password confirmation does not match.',
            // 'role_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must contain both letters and numbers',
            'name.regex'     => 'The name may only contain letters and numbers without spaces',
        ];
    }
}
