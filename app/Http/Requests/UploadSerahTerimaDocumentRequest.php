<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadSerahTerimaDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'pengguna';
    }

    public function rules(): array
    {
        return [
            'signed_handover_document' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'received_condition_photo' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'checklist_frame_safe' => ['sometimes', 'accepted'],
            'checklist_no_tears' => ['sometimes', 'accepted'],
            'checklist_color_normal' => ['sometimes', 'accepted'],
            'checklist_glass_safe' => ['sometimes', 'accepted'],
            'checklist_no_mold' => ['sometimes', 'accepted'],
            'checklist_matches_documentation' => ['sometimes', 'accepted'],
            'condition_notes' => ['nullable', 'string', 'max:2000'],
            'tenant_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
