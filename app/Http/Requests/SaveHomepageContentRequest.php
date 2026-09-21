<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveHomepageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'partner_one' => ['required', 'string', 'max:255'],
            'partner_two' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:5000'],
            'guest_intro' => ['required', 'string', 'max:5000'],
        ];
    }
}
