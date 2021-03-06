<?php

namespace App\Http\Requests\Events;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
        return [
            'name' => 'sometimes|max:250',
            'event_type' => 'sometimes|in:public,private',
            'place' => 'sometimes|max:250',
            'description' => 'sometimes|max:5000',
            'date' => 'required|date|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'duration_minutes' => 'sometimes|numeric|min:0',
            'max_guests_number' => 'sometimes|numeric|min:0',
            'geo_lat' => ['sometimes','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'geo_lng' => ['sometimes','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'applications_ends_at' => 'required|date|date_format:Y-m-d|before:date',
        ];
    }
}
