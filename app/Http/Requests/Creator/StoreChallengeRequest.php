<?php

namespace App\Http\Requests\Creator;

use Illuminate\Foundation\Http\FormRequest;

class StoreChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->hasCreatorPermissions;
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
            'for_gender' => 'required',
            'level' => 'required',
            'description' => 'required',
            'create_group' => 'boolean',
            'images' => 'array|required',
            'images.*.filename' => 'required|string',
            'images.*.path' => 'required|string',
            'youtube_url' => 'string',
            'max_members' => 'numeric',
            'invitation' => 'array',
            'public' => 'boolean',
            'accept_all' => 'boolean',
            'start_at' => 'required',
            'tags' => 'array',
            'finish_at' => '',
            'template.phases' => 'array',
        ];
    }
}
