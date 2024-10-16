<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ];
    }

    public function bodyParameters()
    {
        return [
            'email' => [
                'description' => 'The User Email.',
                'example' => 'sawsan@gmail.com',
            ],
            'password' => [
                'description' => 'The User Password.',
                'example' => '********',
            ],
        ];
    }
}
