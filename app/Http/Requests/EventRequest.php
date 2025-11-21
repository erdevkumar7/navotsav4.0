<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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

        // If not 'publish',skipping all the validation.
        if ($this->input('action') !== 'publish') {
            return [
                'title' => 'required|string',
                'start_date' => 'required|date',
                // 'end_date' => 'required|date',
            ];
        }

        $rules = [
            'title' => 'required|string',
            'location' => 'required|string|max:455',
            'winner_type' => 'required|string|max:255',
            'visiblity' => 'required|in:online,offline,both',
            // 'ticket_quantity' => 'required|numeric|min:10',
            // 'description' => 'required|string',
            'start_date' => 'required|date',
            // 'end_date' => 'required_if:winner_type,automatic',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            // 'draw_time' => 'required_if:winner_type,automatic|date|after_or_equal:end_date',
            'rules' => 'nullable|file|mimes:pdf,doc,docx,txt|max:4096',
            'category_id' => 'required|integer|exists:event_categories,id',
            'multiple_price' => 'nullable|boolean',
            'cause' => 'required|string',
        ];

        $rules['ticket_prices'] = 'required|array|min:1';
        $rules['ticket_prices.*.price'] = 'required|numeric|min:0';
        $rules['ticket_prices.*.quantity'] = 'required|integer|min:1';

        if ($this->input('winner_type') === 'automatic') {
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
            $rules['draw_time'] = 'required|date|after_or_equal:end_date';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $eventId = $this->route('id'); // your route param
            $event = \App\Models\Event::with('banners')->find($eventId);

            // Get keep_media from request (IDs of banners the user kept)
            $keepMedia = $this->input('keep_media', []);
            $keepEventScreen = $this->input('keep_event_screen', []);

            // Check: if no banners remain in DB after removing unkept ones
            $remainingCount = $event ? $event->banners()->whereIn('id', $keepMedia)->count() : 0;

            if ($remainingCount > 0) {
                // User is keeping at least 1 banner → new banners optional
                $rules['banners'] = 'nullable|array';
                $rules['banners.*'] = 'required|image|mimes:jpeg,png,jpg,gif,svg'
                    .'|dimensions:min_width=1200,min_height=600';
            } else {
                // No banners left → must upload at least 1
                $rules['banners'] = 'required|array|min:1';
                $rules['banners.*'] = 'required|image|mimes:jpeg,png,jpg,gif,svg'
                    .'|dimensions:min_width=1200,min_height=600';
            }

            /*
            |--------------------------------------------------------------------------
            | EVENT SCREEN VALIDATION
            |--------------------------------------------------------------------------
            */

            $eventScreenExist = $event && $event->event_screen;
            $userKeepingScreen = count($keepEventScreen) > 0;

            if ($eventScreenExist && $userKeepingScreen) {
                // User kept the old one → optional upload
                $rules['eventscreen'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
                    .'|dimensions:min_width=875,min_height=800';
            } else {
                // User removed old screen → must upload new
                $rules['eventscreen'] = 'required|image|mimes:jpeg,png,jpg,gif,svg'
                    .'|dimensions:min_width=875,min_height=800';
            }

        } else {
            // On create → always required
            $rules['banners'] = 'required|array|min:1';
            $rules['eventscreen'] = 'required|image|mimes:jpeg,png,jpg,gif,svg'
                    .'|dimensions:min_width=875,min_height=800';
            $rules['banners.*'] = 'required|image|mimes:jpeg,png,jpg,gif,svg'
                .'|dimensions:min_width=1200,min_height=600';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'banners.*.dimensions' => 'Each banner must be at least 1200×600 pixels.',
            'banners.*.required' => 'Please upload at least one banner.',
            'banners.*.mimes' => 'Banner must be a jpeg, png, jpg, gif or svg.',
            'banners.*.image' => 'The uploaded file must be an image.', // optional
            'banners.array' => 'Please upload banners as an array of images.',
            'banners.required' => 'At least one banner is required.',

            'ticket_prices.*.price.required' => 'Ticket price field is required',
        ];
    }
}
