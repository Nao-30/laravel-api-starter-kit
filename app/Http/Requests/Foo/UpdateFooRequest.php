<?php

namespace App\Http\Requests\Foo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooRequest extends FormRequest
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
            'name' => 'required|string',
        ];
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Name of the foo',
                'example' => 'sawsan',
            ]
        ];
    }
}
