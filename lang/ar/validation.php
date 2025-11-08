<?php

return [
    // Common Validation Messages
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح.',
    'min' => [
        'string' => 'يجب أن يحتوي :attribute على الأقل على :min أحرف.',
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
    ],
    'max' => [
        'string' => 'يجب ألا يتجاوز :attribute :max حرف.',
        'numeric' => 'يجب ألا تتجاوز قيمة :attribute :max.',
    ],
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'unique' => ':attribute مستخدم مسبقاً.',
    'exists' => ':attribute المحدد غير صحيح.',
    'string' => 'يجب أن يكون :attribute نصاً.',
    'numeric' => 'يجب أن يكون :attribute رقماً.',
    'integer' => 'يجب أن يكون :attribute عدداً صحيحاً.',
    'url' => 'يجب أن يكون :attribute رابط URL صحيح.',
    'date' => 'يجب أن يكون :attribute تاريخاً صحيحاً.',
    'boolean' => 'يجب أن يكون :attribute صحيح أو خطأ.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'between' => [
        'string' => 'يجب أن يكون :attribute بين :min و :max حرف.',
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
    ],

    // Custom Validation Messages for Specific Fields
    'custom' => [
        'email' => [
            'required' => 'البريد الإلكتروني مطلوب.',
            'email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح.',
        ],
        'password' => [
            'required' => 'كلمة المرور مطلوبة.',
            'min' => 'يجب أن تحتوي كلمة المرور على الأقل على 8 أحرف.',
            'confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ],
        'name' => [
            'required' => 'الاسم مطلوب.',
            'min' => 'يجب أن يحتوي الاسم على الأقل على 3 أحرف.',
        ],
        'full_name' => [
            'required' => 'الاسم الكامل مطلوب.',
            'min' => 'يجب أن يحتوي الاسم الكامل على الأقل على 3 أحرف.',
        ],
        'message' => [
            'required' => 'الرسالة مطلوبة.',
            'min' => 'يجب أن تحتوي الرسالة على الأقل على 10 أحرف.',
        ],
        'news_text' => [
            'required' => 'نص الخبر مطلوب.',
            'min' => 'يجب أن يحتوي نص الخبر على الأقل على 50 حرف.',
        ],
    ],

    // Attribute Names
    'attributes' => [
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'name' => 'الاسم',
        'full_name' => 'الاسم الكامل',
        'message' => 'الرسالة',
        'news_text' => 'نص الخبر',
        'phone' => 'رقم الهاتف',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'description' => 'الوصف',
    ],
];
