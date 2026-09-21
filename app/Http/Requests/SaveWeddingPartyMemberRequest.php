<?php

namespace App\Http\Requests;

use App\Models\WeddingPartyMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveWeddingPartyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(WeddingPartyMember::ROLES)],
            'parent_side' => ['exclude_unless:role,Parent', 'required', Rule::in(['Bride', 'Groom'])],
            'description' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240', 'dimensions:max_width=8000,max_height=8000'],
            'remove_photo' => ['sometimes', 'boolean'],
        ];
    }
}
