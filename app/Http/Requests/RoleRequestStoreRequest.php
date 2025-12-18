<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequestStoreRequest extends FormRequest
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
            'requested_role' => 'required|in:Instructor,Teacher',
            'intro_video_path' => [
                'required',
                'file',
                'mimes:mp4,mov,avi,webm',
                'max:51200', // 50 MB
            ],
        ];
    }
}
