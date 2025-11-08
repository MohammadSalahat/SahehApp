<?php

return [
    // Common Validation Messages
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
        'numeric' => 'The :attribute must be at least :min.',
    ],
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
        'numeric' => 'The :attribute must not exceed :max.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'unique' => 'The :attribute has already been taken.',
    'exists' => 'The selected :attribute is invalid.',
    'string' => 'The :attribute must be a string.',
    'numeric' => 'The :attribute must be a number.',
    'integer' => 'The :attribute must be an integer.',
    'url' => 'The :attribute must be a valid URL.',
    'date' => 'The :attribute must be a valid date.',
    'boolean' => 'The :attribute must be true or false.',
    'array' => 'The :attribute must be an array.',
    'between' => [
        'string' => 'The :attribute must be between :min and :max characters.',
        'numeric' => 'The :attribute must be between :min and :max.',
    ],

    // Custom Validation Messages for Specific Fields
    'custom' => [
        'email' => [
            'required' => 'Email is required.',
            'email' => 'Please enter a valid email address.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'min' => 'Password must be at least 8 characters.',
            'confirmed' => 'Password confirmation does not match.',
        ],
        'name' => [
            'required' => 'Name is required.',
            'min' => 'Name must be at least 3 characters.',
        ],
        'full_name' => [
            'required' => 'Full name is required.',
            'min' => 'Full name must be at least 3 characters.',
        ],
        'message' => [
            'required' => 'Message is required.',
            'min' => 'Message must be at least 10 characters.',
        ],
        'news_text' => [
            'required' => 'News text is required.',
            'min' => 'News text must be at least 50 characters.',
        ],
    ],

    // Attribute Names
    'attributes' => [
        'email' => 'email',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'name' => 'name',
        'full_name' => 'full name',
        'message' => 'message',
        'news_text' => 'news text',
        'phone' => 'phone number',
        'address' => 'address',
        'city' => 'city',
        'country' => 'country',
        'title' => 'title',
        'content' => 'content',
        'description' => 'description',
    ],
];
