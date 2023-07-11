<?php

namespace App\Http\Requests\WorkoutUser;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'name' => '',
            'email' => '',
            'phone_number' => '',
            'gender' => '',
            'age' => '',
            'height' => '',
            'weight' => '',
            'level' => '',
            'goals' => 'array',
        ];
    }
}
