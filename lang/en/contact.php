<?php

return [
    'title' => 'Contact Us',
    'subtitle' => 'Reach out for academic information, activities, or program services.',
    'form_error_heading' => 'Please review your form data.',
    'form_title' => 'Send a Message',
    'form_description' => 'Complete the form below. Our team will respond via the email or phone number you provide.',
    'required_note' => 'Fields marked with * are required.',
    'submit_button' => 'Send Message',
    'social_media' => 'Social Media',
    'info_title' => 'Contact Information',
    'fields' => [
        'name' => 'Full Name *',
        'email' => 'Email *',
        'phone' => 'Phone',
        'subject' => 'Subject *',
        'message' => 'Message *',
    ],
    'placeholders' => [
        'name' => 'Full name',
        'email' => 'name@email.com',
        'phone' => '08xxxxxxxxxx',
        'subject' => 'Example: Admissions inquiry',
        'message' => 'Write your message here...',
    ],
    'social' => [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'x' => 'X',
        'linkedin' => 'LinkedIn',
    ],
    'info' => [
        'email_label' => 'Email',
        'phone_label' => 'Phone',
    ],
    'success_message' => 'Your message has been sent successfully. Our team will contact you soon.',
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
            'max' => 'Name must be at most 120 characters.',
        ],
        'email' => [
            'required' => 'Email is required.',
            'email' => 'Please provide a valid email address.',
            'max' => 'Email must be at most 255 characters.',
        ],
        'phone' => [
            'max' => 'Phone number must be at most 30 characters.',
        ],
        'subject' => [
            'required' => 'Subject is required.',
            'max' => 'Subject must be at most 150 characters.',
        ],
        'message' => [
            'required' => 'Message is required.',
            'min' => 'Message must be at least 10 characters.',
            'max' => 'Message must be at most 2000 characters.',
        ],
    ],
    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'phone' => 'phone number',
        'subject' => 'subject',
        'message' => 'message',
    ],
];
