<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChallengeInformationRequest extends FormRequest
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
            'name' => 'required',
            'sort_desc' => 'required',
            'description' => 'required',
            'create_group' => 'boolean',
            'images' => 'array|required',
            'images.*.filename' => 'required|string',
            'images.*.path' => 'required|string',
            'youtube_url' => 'string',
            'max_members' => 'numeric',
            'invitation' => 'array',
            'public' => 'boolean',
            'for_gender' => '',
            'accept_all' => 'boolean',
            'start_at' => 'required',
            'tags' => 'array',
            'finish_at' => '',
        ];
    }
}
