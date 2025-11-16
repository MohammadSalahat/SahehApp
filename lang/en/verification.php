<?php

return [
    // Page Title
    'page_title' => 'AI Verification Result - Saheh Platform',
    'main_title' => 'AI Verification Result',
    'subtitle' => 'Intelligent analysis of text using semantic similarity and language processing',
    'back_to_home' => 'Back to Homepage',

    // Text Quality Analysis
    'quality_analysis_title' => 'Input Text Quality Analysis',
    'word_count' => 'Word Count',
    'arabic_ratio' => 'Arabic Text Ratio',
    'legal_terms_count' => 'Legal Terms',
    'quality_score' => 'Quality Score',
    'character_count' => 'Character Count',
    'legal_keyword_count' => 'Legal Keywords',
    'is_legal_text' => 'Legal Text',

    // Status Messages - Error
    'error_title' => 'System Error',
    'error_message' => 'System Error',

    // Status Messages - Potentially Fake
    'potentially_fake_title' => '⚠️ Warning: Potentially False News',
    'potentially_fake_subtitle' => 'AI detected similarity with known fake news',
    'ai_recommendation_title' => 'AI System Recommendation',

    // Similarity Analysis
    'similarity_title' => 'Semantic Similarity Score',
    'similarity_low' => 'Low',
    'similarity_high' => 'High',
    'similarity_level' => 'Similarity Level',

    // Detailed Metrics
    'semantic_similarity' => 'Semantic Similarity',
    'lexical_overlap' => 'Lexical Overlap',
    'legal_terms_overlap' => 'Legal Terms',
    'common_legal_entities' => 'Common Legal Terms',
    'analysis_method' => 'Analysis Method',
    'analysis_method_semantic' => 'Semantic similarity using Sentence Transformers models',
    'analysis_method_tfidf' => 'TF-IDF analysis and cosine similarity',

    // Best Match Details
    'best_match_title' => 'Similar Fake News',
    'source_label' => 'Source',
    'confidence_label' => 'Detection Confidence',

    // Additional Matches
    'additional_matches_title' => 'Other Similar Fake News',

    // Status Messages - Caution (Medium Similarity)
    'caution_title' => 'Alert: Medium Similarity',
    'similar_news_found' => 'Similar News Found',

    // Status Messages - Safe (No Match)
    'safe_title' => '✅ No Similarity Found',
    'safe_subtitle' => 'The text does not resemble known fake news',
    'safe_analysis_title' => 'Smart Analysis Result',
    'safe_default_message' => 'The text you entered does not resemble fake news in our database.',
    'important_note_title' => '💡 Important Note',
    'important_note_text' => 'No similarity does not necessarily mean the news is true. Always verify with trusted official sources such as:',
    'source_spa' => '• Saudi Press Agency (SPA)',
    'source_moj' => '• Official Ministry of Justice Website',
    'source_gov' => '• Official Government Websites',

    // Submitted Text Section
    'submitted_text_title' => 'Analyzed Text',
    'preprocessed_text_label' => 'Text After Language Processing (NLP)',

    // Feedback Section
    'feedback_title' => 'Rate Result Accuracy',

    // Action Buttons
    'verify_another' => 'Verify Another News',
    'analysis_footer' => 'Analysis performed using AI and Natural Language Processing (NLP) for Arabic',
    
    // Navigation Messages
    'refresh_warning' => 'Results will be lost if you refresh. Navigate using the buttons instead.',
    'session_expired' => 'Verification result not available. Please try again.',
];
