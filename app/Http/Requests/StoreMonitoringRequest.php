<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonitoringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSurveyor();
    }

    public function rules(): array
    {
        return [
            'planting_record_id' => 'required|exists:planting_records,id',
            'alive_count' => 'required|integer|min:0',
            'dead_count' => 'required|integer|min:0',
            'monitored_at' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240', // Max 10MB
            'geotag_lat' => 'nullable|string|max:50',
            'geotag_lng' => 'nullable|string|max:50',
        ];
    }
}
