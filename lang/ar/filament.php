<?php

return [
    // Resource Names (Plural)
    'resources' => [
        'feedback' => 'التقييمات',
        'contact_requests' => 'طلبات التواصل',
        'users' => 'المستخدمون',
        'sources' => 'المصادر',
        'fake_news' => 'الأخبار المزيفة',
        'datasets_fake_news' => 'قواعد بيانات الأخبار المزيفة',
    ],

    // Resource Names (Singular)
    'resource' => [
        'feedback' => 'تقييم',
        'contact_request' => 'طلب تواصل',
        'user' => 'مستخدم',
        'source' => 'مصدر',
        'fake_news_item' => 'خبر مزيف',
    ],

    // Navigation Groups
    'navigation_groups' => [
        'content' => 'المحتوى',
        'system' => 'النظام',
        'users' => 'المستخدمون',
        'settings' => 'الإعدادات',
    ],

    // Common Labels
    'labels' => [
        'id' => 'المعرّف',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'message' => 'الرسالة',
        'status' => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'actions' => 'الإجراءات',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'description' => 'الوصف',
        'url' => 'الرابط',
        'type' => 'النوع',
        'category' => 'التصنيف',
        'rating' => 'التقييم',
        'user_id' => 'المستخدم',
        'phone' => 'رقم الهاتف',
        'subject' => 'الموضوع',
        'priority' => 'الأولوية',
        'confidence_score' => 'درجة الثقة',
        'origin_dataset_name' => 'مصدر البيانات',
        'content_hash' => 'معرف المحتوى',
        'detected_at' => 'تاريخ الاكتشاف',
        'added_by_ai' => 'مضاف بواسطة الذكاء الاصطناعي',
        'email_verified_at' => 'تاريخ تأكيد البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'remember_token' => 'رمز التذكر',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'لوحة التحكم',
        'welcome' => 'مرحباً بك في لوحة التحكم',
        'overview' => 'نظرة عامة',
        'statistics' => 'الإحصائيات',
        'recent_activity' => 'النشاط الأخير',
        'quick_actions' => 'إجراءات سريعة',
    ],

    // Navigation
    'navigation' => [
        'dashboard' => 'لوحة التحكم',
        'settings' => 'الإعدادات',
        'profile' => 'الملف الشخصي',
        'logout' => 'تسجيل الخروج',
    ],

    // Status Values
    'status_values' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'draft' => 'مسودة',
        'published' => 'منشور',
    ],

    // Messages
    'messages' => [
        'created' => 'تم الإنشاء بنجاح',
        'updated' => 'تم التحديث بنجاح',
        'deleted' => 'تم الحذف بنجاح',
        'error' => 'حدث خطأ، يرجى المحاولة مرة أخرى',
        'no_records' => 'لا توجد سجلات',
        'confirm_delete' => 'هل أنت متأكد من الحذف؟',
        'search_placeholder' => 'البحث...',
    ],

    // Feedback Resource
    'feedback' => [
        'title' => 'التقييمات',
        'singular' => 'تقييم',
        'user' => 'المستخدم',
        'rating' => 'التقييم',
        'comment' => 'التعليق',
        'verification_result' => 'نتيجة التحقق',
        'is_accurate' => 'دقيق',
        'created_at' => 'تاريخ الإنشاء',
    ],

    // Contact Requests Resource
    'contact_requests' => [
        'title' => 'طلبات التواصل',
        'singular' => 'طلب تواصل',
        'full_name' => 'الاسم الكامل',
        'email' => 'البريد الإلكتروني',
        'message' => 'الرسالة',
        'status' => 'الحالة',
        'pending' => 'قيد الانتظار',
        'in_progress' => 'قيد المعالجة',
        'resolved' => 'تم الحل',
        'closed' => 'مغلق',
        'created_at' => 'تاريخ الإرسال',
    ],

    // Users Resource
    'users' => [
        'title' => 'المستخدمون',
        'singular' => 'مستخدم',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'role' => 'الدور',
        'verified' => 'موثق',
        'active' => 'نشط',
        'created_at' => 'تاريخ التسجيل',
    ],

    // Sources Resource
    'sources' => [
        'title' => 'المصادر',
        'singular' => 'مصدر',
        'name' => 'اسم المصدر',
        'url' => 'رابط المصدر',
        'type' => 'نوع المصدر',
        'description' => 'وصف المصدر',
        'is_active' => 'نشط',
        'credibility_score' => 'درجة المصداقية',
    ],

    // Fake News Dataset Resource
    'fake_news' => [
        'title' => 'الأخبار المزيفة',
        'singular' => 'خبر مزيف',
        'title_field' => 'عنوان الخبر',
        'content' => 'محتوى الخبر',
        'origin_dataset' => 'مصدر البيانات',
        'category' => 'التصنيف',
        'is_verified' => 'موثق',
        'similarity_score' => 'درجة التشابه',
    ],

    // Common Actions
    'actions' => [
        'create' => 'إنشاء',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'view' => 'عرض',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'back' => 'رجوع',
        'submit' => 'إرسال',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'bulk_delete' => 'حذف متعدد',
        'refresh' => 'تحديث',
        'reset' => 'إعادة تعيين',
    ],

    // Status Messages
    'status' => [
        'pending' => 'قيد الانتظار',
        'in_progress' => 'قيد المعالجة',
        'completed' => 'مكتمل',
        'resolved' => 'تم الحل',
        'closed' => 'مغلق',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'verified' => 'موثق',
        'unverified' => 'غير موثق',
    ],

    // Sections
    'sections' => [
        'basic_info' => 'المعلومات الأساسية',
        'details' => 'التفاصيل',
        'additional_info' => 'معلومات إضافية',
        'settings' => 'الإعدادات',
        'security' => 'الأمان',
        'contact_info' => 'معلومات الاتصال',
    ],
];
