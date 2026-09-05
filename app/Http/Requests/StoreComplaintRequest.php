<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public accessible
    }

    public function rules(): array
    {
        return [
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:3000',
        ];
    }
}
