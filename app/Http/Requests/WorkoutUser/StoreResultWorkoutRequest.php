<?php

namespace App\Http\Requests\WorkoutUser;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultWorkoutRequest extends FormRequest
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
            'plan_id' => 'required',
            'phase_session_id' => 'required',
            'duration' => 'string',
            'calories_burned' => 'numeric',
            'bpm' => 'numeric',
            'video' => '',
            'notify_creator' => 'boolean',
            'feedback' => '',
        ];
    }
}
