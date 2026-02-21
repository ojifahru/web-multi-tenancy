<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 120 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'phone.max' => 'Nomor telepon maksimal 30 karakter.',
            'subject.required' => 'Subjek wajib diisi.',
            'subject.max' => 'Subjek maksimal 150 karakter.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'subject' => 'subjek',
            'message' => 'pesan',
        ];
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $email = $this->input('email');
        $phone = $this->input('phone');
        $subject = $this->input('subject');
        $message = $this->input('message');

        $this->merge([
            'name' => is_string($name) ? trim($name) : $name,
            'email' => is_string($email) ? trim($email) : $email,
            'phone' => is_string($phone) ? trim($phone) : $phone,
            'subject' => is_string($subject) ? trim($subject) : $subject,
            'message' => is_string($message) ? trim($message) : $message,
        ]);

        if ($this->input('phone') === '') {
            $this->merge(['phone' => null]);
        }
    }
}
