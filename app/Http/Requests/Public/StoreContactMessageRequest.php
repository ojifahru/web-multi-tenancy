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
            'name.required' => __('contact.validation.name.required'),
            'name.max' => __('contact.validation.name.max'),
            'email.required' => __('contact.validation.email.required'),
            'email.email' => __('contact.validation.email.email'),
            'email.max' => __('contact.validation.email.max'),
            'phone.max' => __('contact.validation.phone.max'),
            'subject.required' => __('contact.validation.subject.required'),
            'subject.max' => __('contact.validation.subject.max'),
            'message.required' => __('contact.validation.message.required'),
            'message.min' => __('contact.validation.message.min'),
            'message.max' => __('contact.validation.message.max'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('contact.attributes.name'),
            'email' => __('contact.attributes.email'),
            'phone' => __('contact.attributes.phone'),
            'subject' => __('contact.attributes.subject'),
            'message' => __('contact.attributes.message'),
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
