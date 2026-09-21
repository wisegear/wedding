<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveWeddingPartyPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'wedding_party_title' => ['required', 'string', 'max:255'],
            'wedding_party_intro' => ['required', 'string', 'max:5000'],
        ];
    }
}
