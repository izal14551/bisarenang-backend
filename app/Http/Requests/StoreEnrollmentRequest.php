<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_ADMIN;
    }

    public function rules(): array
    {
        return [
            'member_id'   => ['required', 'exists:swim_members,id'],
            'class_id'    => ['required', 'exists:swim_classes,id'],
            'schedule_id' => ['nullable', 'exists:class_schedules,id'],
        ];
    }
}
