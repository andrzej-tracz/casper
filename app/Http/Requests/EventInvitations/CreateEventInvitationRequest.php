<?php

namespace App\Http\Requests\EventInvitations;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

class CreateEventInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->user();
        $rules = [
            'required', 'numeric'
        ];

        if ($user) {
            $rules[] = Rule::notIn([ $user->id ]);
        }

        return [
            'user_id' => $rules,
        ];
    }

    public function messages()
    {
        return [
            'user_id.not_in' => __('Can not invite to event yourself.')
        ];
    }
}
