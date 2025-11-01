<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveEventDateRequest extends FormRequest
{
    protected $errorBag = "event-date_create";

    public function rules(): array
    {
        $event = $this->route('event');
	$request = $this;
        return [
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time',
                Rule::unique('event_dates')->where(function ($query) 
                    use ($request, $event) {
                    return $query->where('event_id', $event->id)
                         ->where('date', $request->date)
                         ->where('start_time', $request->start_time)
                         ->where('end_time', $request->end_time);
                }),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.date_format' => 'The start time field must match the 24-hour format.', 
            'end_time.date_format' => 'The end time field must match the 24-hour format.', 
            'end_time.unique' => 'Same date and time already exists.',
        ];
    }
}
