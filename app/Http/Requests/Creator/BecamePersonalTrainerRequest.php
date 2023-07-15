<?php

namespace App\Http\Requests\Creator;

use Illuminate\Foundation\Http\FormRequest;

class BecamePersonalTrainerRequest extends FormRequest
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
            'user' => 'required',
            'user.name' => 'string',
            'user.avatar' => 'required',
            'user.phone_number' => 'required',
            "age" => 'required',
            "address" => 'required',
            "workout_training_media" =>  '',
            "certificate" => '',
            "certificate_issuer" => '',
            "work_type" => 'required',
            "techniques" => 'required',
            "desired_salary" => 'required',
            "introduce" => 'required',
            "gender" => 'required',
            "zalo" => '',
            "facebook" => '',
            "youtube" => '',
        ];
    }
}
