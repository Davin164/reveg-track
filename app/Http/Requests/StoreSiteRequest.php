<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isManager();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'area_hectares' => 'required|numeric|min:0.01|max:10000',
            'status' => 'required|in:pending,in_progress,completed,verified',
            'managed_by' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:2000',
        ];
    }
}
