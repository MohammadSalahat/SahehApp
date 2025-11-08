<?php

return [
    // Resource Names (Plural)
    'resources' => [
        'feedback' => 'Feedback',
        'contact_requests' => 'Contact Requests',
        'users' => 'Users',
        'sources' => 'Sources',
        'fake_news' => 'Fake News',
        'datasets_fake_news' => 'Fake News Datasets',
    ],

    // Resource Names (Singular)
    'resource' => [
        'feedback' => 'Feedback',
        'contact_request' => 'Contact Request',
        'user' => 'User',
        'source' => 'Source',
        'fake_news_item' => 'Fake News Item',
    ],

    // Navigation Groups
    'navigation_groups' => [
        'content' => 'Content',
        'system' => 'System',
        'users' => 'Users',
        'settings' => 'Settings',
    ],

    // Common Labels
    'labels' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'message' => 'Message',
        'status' => 'Status',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'actions' => 'Actions',
        'title' => 'Title',
        'content' => 'Content',
        'description' => 'Description',
        'url' => 'URL',
        'type' => 'Type',
        'category' => 'Category',
    ],

    // Feedback Resource
    'feedback' => [
        'title' => 'Feedback',
        'singular' => 'Feedback',
        'user' => 'User',
        'rating' => 'Rating',
        'comment' => 'Comment',
        'verification_result' => 'Verification Result',
        'is_accurate' => 'Accurate',
        'created_at' => 'Created At',
    ],

    // Contact Requests Resource
    'contact_requests' => [
        'title' => 'Contact Requests',
        'singular' => 'Contact Request',
        'full_name' => 'Full Name',
        'email' => 'Email',
        'message' => 'Message',
        'status' => 'Status',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'created_at' => 'Submitted At',
    ],

    // Users Resource
    'users' => [
        'title' => 'Users',
        'singular' => 'User',
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
        'verified' => 'Verified',
        'active' => 'Active',
        'created_at' => 'Registration Date',
    ],

    // Sources Resource
    'sources' => [
        'title' => 'Sources',
        'singular' => 'Source',
        'name' => 'Source Name',
        'url' => 'Source URL',
        'type' => 'Source Type',
        'description' => 'Source Description',
        'is_active' => 'Active',
        'credibility_score' => 'Credibility Score',
    ],

    // Fake News Dataset Resource
    'fake_news' => [
        'title' => 'Fake News',
        'singular' => 'Fake News',
        'title_field' => 'News Title',
        'content' => 'News Content',
        'origin_dataset' => 'Dataset Origin',
        'category' => 'Category',
        'is_verified' => 'Verified',
        'similarity_score' => 'Similarity Score',
    ],

    // Common Actions
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'bulk_delete' => 'Bulk Delete',
    ],

    // Status Messages
    'status' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'verified' => 'Verified',
        'unverified' => 'Unverified',
    ],

    // Sections
    'sections' => [
        'basic_info' => 'Basic Information',
        'details' => 'Details',
        'additional_info' => 'Additional Information',
        'settings' => 'Settings',
        'security' => 'Security',
        'contact_info' => 'Contact Information',
    ],
];
