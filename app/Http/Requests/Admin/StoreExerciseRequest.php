<?php

namespace App\Http\Requests\Admin;

use App\Models\Exercise;
use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (($id = $this->route('id')) !== null) {
            try {
                $exercise = Exercise::findOrFail($id);
                return $this->user()->can('update', $exercise);
            } catch (\Throwable $th) {
                abort(404, 'cannot found this exercise');
            }
        }
        return $this->user()->can('create', Exercise::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'level' => 'required',
            'type' => '',
            'requirement_unit' => 'required',
            'requirement_initial' => 'required',
            'equipment_id' => '',
            'group_tags' => 'array',
            'muscles' => 'required',
            'description' => '',
            'gif.filename' => 'required',
            'gif.path' => 'required',
            'image.filename' => 'required',
            'image.path' => 'required',
            'video' => '',
        ];
    }
}
