<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // bisa di-tighten: return $this->user()?->role === User::ROLE_ADMIN;
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id'    => ['required', 'exists:swim_classes,id'],
            'member_id'   => ['required', 'exists:swim_members,id'],
            'schedule_id' => ['nullable', 'exists:class_schedules,id'],
        ];
    }
}
