<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProjectInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('manage', $this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:users,email'
        ];
    }

    /**
     * Get the validation error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.exists' => 'The user you are inviting is not registered to Birdboard.'
        ];
    }
}
