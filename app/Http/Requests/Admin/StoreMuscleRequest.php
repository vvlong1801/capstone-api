<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMuscleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->hasAdminPermissions;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:muscles,name,'.$this->route('muscle').',id',
            'image.filename' => 'required',
            'image.path' => 'required',
            'icon.filename' => '',
            'icon.path' => '',
            'description' => '',
        ];
    }
}
