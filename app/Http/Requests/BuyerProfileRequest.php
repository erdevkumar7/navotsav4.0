<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:32', 'unique:users,phone,' . auth()->id()],
            'dob' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'avatar' => "nullable|image|mimes:jpg,jpeg,png,svg",
            'preferred_language' => 'required',
            'notification' => 'nullable'
        ];
    }
}
