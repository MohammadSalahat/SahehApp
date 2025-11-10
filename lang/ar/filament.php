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
        'feedback_information' => 'معلومات التقييم',
        'feedback_info_description' => 'تفاصيل التقييم الأساسية ومعلومات المقال',
        'feedback_message' => 'رسالة التقييم',
        'feedback_message_description' => 'رسالة التقييم التفصيلية من المستخدم',
        'user_information' => 'معلومات المستخدم',
        'user_info_description' => 'تفاصيل المستخدم الذي قدم التقييم',
        'article_rating_information' => 'معلومات المقال والتقييم',
        'article_rating_description' => 'معلومات عن المقال وتقييم المستخدم',
        'timestamps' => 'التواريخ',
        'timestamps_description' => 'تواريخ تقديم التقييم وتعديله',

        // Fields
        'user' => 'المستخدم',
        'user_name' => 'اسم المستخدم',
        'user_email' => 'بريد المستخدم',
        'article_title' => 'عنوان المقال',
        'rating' => 'التقييم',
        'user_rating' => 'تقييم المستخدم',
        'message' => 'الرسالة',
        'user_message' => 'رسالة المستخدم',
        'verification_result' => 'نتيجة التحقق',
        'verification' => 'التحقق',
        'verification_status' => 'حالة التحقق',
        'submitted_at' => 'تاريخ الإرسال',

        // Placeholders
        'placeholders' => [
            'user' => 'اختر المستخدم الذي قدم التقييم',
            'article_title' => 'أدخل عنوان المقال',
            'rating' => 'اختر التقييم',
            'message' => 'أدخل رسالة التقييم التفصيلية...',
            'verification' => 'اختر نتيجة التحقق',
        ],

        // Rating Options
        'ratings' => [
            'poor' => 'ضعيف',
            'fair' => 'مقبول',
            'good' => 'جيد',
            'very_good' => 'جيد جدًا',
            'excellent' => 'ممتاز',
            'star' => 'نجمة',
            'stars' => 'نجوم',
        ],

        // Verification Values
        'real' => 'حقيقي',
        'fake' => 'مزيف',
        'uncertain' => 'غير مؤكد',
        'pending' => 'قيد الانتظار',
        'not_verified' => 'لم يتم التحقق بعد',

        // Messages
        'no_message' => 'لا توجد رسالة',
        'no_message_provided' => 'لم يتم تقديم رسالة',
        'name_copied' => 'تم نسخ الاسم!',
        'email_copied' => 'تم نسخ البريد الإلكتروني!',
        'title_copied' => 'تم نسخ العنوان!',
        'no_feedback' => 'لا توجد تقييمات',
        'no_feedback_description' => 'لا توجد إدخالات تقييم بعد. سيرى المستخدمون تقييماتهم هنا بمجرد تقديمها.',
        'updated_at' => 'آخر تحديث',
        'unknown' => 'غير معروف',
        'never_updated' => 'لم يتم التحديث',
    ],

    // Contact Requests Resource
    'contact_requests' => [
        'title' => 'طلبات التواصل',
        'singular' => 'طلب تواصل',
        'contact_information' => 'معلومات الاتصال',
        'contact_info_description' => 'تفاصيل الاتصال بالعميل ومعلومات الطلب',
        'request_message' => 'رسالة الطلب',
        'request_message_description' => 'الرسالة الأصلية من العميل',
        'followup_information' => 'معلومات المتابعة',
        'followup_info_description' => 'ملاحظات داخلية وسجل الاتصال',
        'client_information' => 'معلومات العميل',
        'followup_details' => 'تفاصيل المتابعة',
        'timestamps' => 'التواريخ',
        'timestamps_description' => 'تواريخ تقديم الطلب وتعديله',

        // Fields
        'full_name' => 'الاسم الكامل',
        'email' => 'عنوان البريد الإلكتروني',
        'email_address' => 'عنوان البريد الإلكتروني',
        'message' => 'الرسالة',
        'client_message' => 'رسالة العميل',
        'status' => 'حالة الطلب',
        'request_status' => 'حالة الطلب',
        'last_contact_date' => 'تاريخ آخر اتصال',
        'last_contacted_at' => 'تاريخ آخر اتصال',
        'notes' => 'الملاحظات',
        'internal_notes' => 'الملاحظات الداخلية',
        'submitted_at' => 'تاريخ الإرسال',
        'created_at' => 'تاريخ الإرسال',

        // Status Values
        'new' => 'جديد',
        'read' => 'مقروء',
        'responded' => 'تم الرد',
        'archived' => 'مؤرشف',
        'pending' => 'قيد الانتظار',
        'in_progress' => 'قيد المعالجة',
        'resolved' => 'تم الحل',
        'closed' => 'مغلق',

        // Placeholders
        'placeholders' => [
            'name' => 'أدخل الاسم الكامل',
            'email' => 'أدخل عنوان البريد الإلكتروني',
            'message' => 'أدخل رسالتك هنا...',
        ],

        // Helper Texts
        'helper_texts' => [
            'created_at' => 'وقت إنشاء هذا الطلب',
            'last_contacted_at' => 'آخر مرة تواصلت مع هذا العميل',
            'notes' => 'أضف أي ملاحظات أو تعليقات حول هذا الطلب',
        ],

        // Messages
        'no_message' => 'لا توجد رسالة',
        'no_message_provided' => 'لم يتم تقديم رسالة',
        'no_notes' => 'لم يتم إضافة ملاحظات',
        'never_contacted' => 'لم يتم الاتصال',
        'name_copied' => 'تم نسخ الاسم!',
        'email_copied' => 'تم نسخ البريد الإلكتروني!',
        'no_contact_requests' => 'لا توجد طلبات تواصل',
        'no_contact_requests_description' => 'لا توجد طلبات تواصل بعد. ستظهر استفسارات العملاء هنا.',
        'updated_at' => 'آخر تحديث',
    ],

    // Users Resource
    'users' => [
        'title' => 'المستخدمون',
        'singular' => 'مستخدم',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'role' => 'الدور',
        'user_role' => 'دور المستخدم',
        'verified' => 'موثّق',
        'unverified' => 'غير موثّق',
        'active' => 'نشط',
        'created_at' => 'تاريخ التسجيل',
        'updated_at' => 'آخر تحديث',
        'email_verified_at' => 'تاريخ تأكيد البريد الإلكتروني',
        'email_verified' => 'تحقق البريد الإلكتروني',
        'email_unverified' => 'البريد الإلكتروني غير موثّق',
        'email_verification' => 'تحقق البريد الإلكتروني',
        'email_not_verified' => 'لم يتم التحقق من البريد الإلكتروني',
        'email_verification_required' => 'مطلوب تحقق من البريد الإلكتروني',
        'deleted_at' => 'تاريخ الحذف',
        'deleted_users' => 'المستخدمون المحذوفون',
        'user_information' => 'معلومات المستخدم',
        'basic_info' => 'معلومات أساسية عن المستخدم',
        'date_information' => 'تواريخ',
        'user_profile' => 'ملف المستخدم',
        'user_profile_description' => 'معلومات وتفاصيل حساب المستخدم',
        'account_status' => 'حالة الحساب',
        'account_status_description' => 'معلومات التحقق والأمان',
        'account_activity' => 'نشاط الحساب',
        'account_activity_description' => 'تواريخ إنشاء الحساب وتعديله',
        'account_created' => 'تم إنشاء الحساب',
        'account_deleted' => 'تم حذف الحساب',
        'last_updated' => 'آخر تحديث',
        'user' => 'مستخدم',
        'administrator' => 'مدير',
        'two_factor' => 'المصادقة الثنائية',
        'two_factor_authentication' => 'المصادقة الثنائية',
        'two_factor_enabled' => 'المصادقة الثنائية مفعّلة',
        'two_factor_disabled' => 'المصادقة الثنائية معطّلة',
        'two_factor_filter' => 'المصادقة الثنائية مفعّلة',
        'enabled' => 'مفعّل',
        'disabled' => 'معطّل',
        'not_enabled' => 'غير مفعّل',
        'not_verified' => 'غير موثّق',
        'verified_on' => 'تم التوثيق في :date',
        'name_copied' => 'تم نسخ الاسم!',
        'email_copied' => 'تم نسخ البريد الإلكتروني!',
        'no_users' => 'لا يوجد مستخدمون',
        'no_users_description' => 'ابدأ بإنشاء أول مستخدم.',
        'unknown' => 'غير معروف',
        'never_updated' => 'لم يتم التحديث',

        'placeholders' => [
            'name' => 'أدخل الاسم الكامل',
            'email' => 'أدخل عنوان البريد الإلكتروني',
            'password' => 'أدخل كلمة مرور آمنة',
        ],

        'helpers' => [
            'select_role' => 'حدد الدور المناسب لهذا المستخدم',
            'password_length' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
        ],
    ],

    // Sources Resource
    'sources' => [
        'title' => 'المصادر',
        'singular' => 'مصدر',
        'source_information' => 'معلومات المصدر',
        'source_info_description' => 'معلومات أساسية عن مصدر الأخبار',
        'reliability_assessment' => 'تقييم الموثوقية',
        'reliability_description' => 'مقاييس الموثوقية وحالة المصدر',
        'timestamps' => 'التواريخ',
        'timestamps_description' => 'تواريخ إضافة المصدر وتعديله وحذفه',

        // Fields
        'name' => 'اسم المصدر',
        'source_name' => 'اسم المصدر',
        'url' => 'رابط الموقع',
        'website_url' => 'رابط الموقع',
        'description' => 'الوصف',
        'reliability_score' => 'درجة الموثوقية',
        'reliability' => 'الموثوقية',
        'is_active' => 'مصدر نشط',
        'status' => 'الحالة',
        'source_status' => 'حالة المصدر',
        'added_at' => 'تاريخ الإضافة',
        'updated_at' => 'آخر تحديث',
        'deleted_at' => 'تاريخ الحذف',
        'monitoring_status' => 'حالة المراقبة',

        // Placeholders
        'placeholders' => [
            'name' => 'أدخل اسم مصدر الأخبار (مثال: CNN, BBC)',
            'url' => 'https://example.com',
            'description' => 'وصف موجز لمصدر الأخبار...',
        ],

        // Helper Texts
        'helper_texts' => [
            'reliability_score' => 'درجة الموثوقية من 0 (غير موثوق) إلى 1 (موثوق جدًا)',
            'is_active' => 'ما إذا كان يتم مراقبة هذا المصدر بنشاط',
        ],

        // Values
        'active_sources' => 'المصادر النشطة',
        'inactive_sources' => 'المصادر غير النشطة',
        'high_reliability' => 'موثوقية عالية (>80%)',
        'low_reliability' => 'موثوقية منخفضة (<40%)',
        'active_monitored' => 'نشط - تتم المراقبة',
        'inactive_not_monitored' => 'غير نشط - لا تتم المراقبة',
        'no_description' => 'لا يوجد وصف',
        'source_name_copied' => 'تم نسخ اسم المصدر!',
        'url_copied' => 'تم نسخ الرابط!',
        'never_updated' => 'لم يتم التحديث',
        'no_sources' => 'لا توجد مصادر أخبار',
        'no_sources_description' => 'أضف مصادر أخبار لمراقبتها للكشف عن الأخبار المزيفة.',
    ],

    // Fake News Dataset Resource
    'datasets_fake_news' => [
        'title' => 'قواعد بيانات الأخبار المزيفة',
        'singular' => 'خبر مزيف',
        'article_information' => 'معلومات المقال',
        'article_info_description' => 'معلومات أساسية عن مقال الأخبار المزيفة',
        'detection_information' => 'معلومات الكشف',
        'detection_info_description' => 'نتائج الكشف بالذكاء الاصطناعي ومقاييس الثقة',
        'source_information' => 'معلومات المصدر',
        'source_info_description' => 'أصل قاعدة البيانات وتحديد المحتوى',
        'timestamps' => 'التواريخ',
        'timestamps_description' => 'تواريخ الإنشاء والتعديل والحذف',

        // Fields
        'article_title' => 'عنوان المقال',
        'article_content' => 'محتوى المقال',
        'origin_dataset' => 'قاعدة البيانات الأصلية',
        'content_hash' => 'معرّف المحتوى',
        'detected_at' => 'تاريخ الكشف',
        'confidence_score' => 'درجة ثقة الذكاء الاصطناعي',
        'added_by_ai' => 'مضاف بواسطة الذكاء الاصطناعي',
        'ai_confidence' => 'ثقة الذكاء الاصطناعي',
        'source_dataset' => 'مصدر قاعدة البيانات',
        'ai_detected' => 'كشف بالذكاء الاصطناعي',
        'detection_date' => 'تاريخ الكشف',
        'detection_method' => 'طريقة الكشف',
        'added_at' => 'تاريخ الإضافة',
        'updated_at' => 'آخر تحديث',
        'deleted_at' => 'تاريخ الحذف',

        // Placeholders
        'placeholders' => [
            'title' => 'أدخل عنوان المقال',
            'content' => 'أدخل محتوى المقال الكامل...',
            'origin_dataset' => 'اسم مصدر البيانات (مثال: LIAR, FakeNewsNet)',
            'content_hash' => 'معرّف SHA-256 للمحتوى',
        ],

        // Helper Texts
        'helper_texts' => [
            'content_hash' => 'معرّف فريد لاكتشاف المحتوى المكرر',
            'confidence_score' => 'مستوى ثقة الذكاء الاصطناعي (0-1)',
            'added_by_ai' => 'ما إذا تم اكتشاف هذا الإدخال تلقائيًا بواسطة الذكاء الاصطناعي',
        ],

        // Values
        'unknown_dataset' => 'قاعدة بيانات غير معروفة',
        'automatically_detected' => 'كشف تلقائي بالذكاء الاصطناعي',
        'manually_added' => 'مضاف يدويًا',
        'never_updated' => 'لم يتم التحديث',
        'liar_dataset' => 'قاعدة بيانات LIAR',
        'fake_news_net' => 'FakeNewsNet',
        'isot_fake_news' => 'أخبار ISOT المزيفة',
        'arabic_fake_news' => 'الأخبار المزيفة العربية',
        'high_confidence' => 'ثقة عالية (>80%)',
        'detected_from' => 'كشف من',
        'detected_until' => 'كشف حتى',

        // Messages
        'title_copied' => 'تم نسخ العنوان!',
        'hash_copied' => 'تم نسخ المعرّف!',
        'no_fake_news_detected' => 'لم يتم اكتشاف أخبار مزيفة',
        'no_fake_news_description' => 'لم يكتشف نظام الذكاء الاصطناعي أي مقالات أخبار مزيفة بعد. ستظهر نتائج الكشف هنا.',
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
