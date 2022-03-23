<?php

use App\Fresns\Panel\Http\Middleware\ChangeLocale;

return [
    'news_url' => 'https://fresns.org/news.json',

    'version_url' => 'https://fresns.org/version.json',

    'middleware' => [
        'web',
        ChangeLocale::class,
    ],

    'route_blacklist' => [
        'dashboard/',
        'systems/',
        'operations/',
        'expands/',
        'plugins/',
        'clients/',
        'market/',
    ],

    'langs' => [
        'en' => 'English - English',
        'es' => 'Español - Spanish',
        'fr' => 'Français - French',
        'de' => 'Deutsch - German',
        'it' => 'Italiano - Italian',
        'ja' => '日本語 - Japanese',
        'ko' => '한국어 - Korean',
        'ru' => 'Русский - Russian',
        'pt' => 'Português - Portuguese',
        'id' => 'Bahasa Indonesia - Indonesian',
        'hi' => 'हिन्दी - Hindi',
        'zh-Hans' => '简体中文 - Chinese (Simplified)',
        'zh-Hant' => '繁體中文 - Chinese (Traditional)',
    ],

    'role_default_permission' => '[{"permKey":"content_view","permValue":true,"permStatus":"","isCustom":false},{"permKey":"dialog","permValue":true,"permStatus":"","isCustom":false},{"permKey":"post_publish","permValue":true,"permStatus":"","isCustom":false},{"permKey":"post_review","permValue":false,"permStatus":"","isCustom":false},{"permKey":"post_email_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"post_phone_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"post_prove_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"post_limit_status","permValue":false,"permStatus":"","isCustom":false},{"permKey":"post_limit_type","permValue":1,"permStatus":"","isCustom":false},{"permKey":"post_limit_period_start","permValue":"2021-08-01 22:30:00","permStatus":"","isCustom":false},{"permKey":"post_limit_period_end","permValue":"2021-08-02 08:00:00","permStatus":"","isCustom":false},{"permKey":"post_limit_cycle_start","permValue":"23:00:00","permStatus":"","isCustom":false},{"permKey":"post_limit_cycle_end","permValue":"08:30:00","permStatus":"","isCustom":false},{"permKey":"post_limit_rule","permValue":1,"permStatus":"","isCustom":false},{"permKey":"comment_publish","permValue":true,"permStatus":"","isCustom":false},{"permKey":"comment_review","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_email_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_phone_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_prove_verify","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_limit_status","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_limit_type","permValue":1,"permStatus":"","isCustom":false},{"permKey":"comment_limit_period_start","permValue":"2021-08-01 22:30:00","permStatus":"","isCustom":false},{"permKey":"comment_limit_period_end","permValue":"2021-08-02 08:00:00","permStatus":"","isCustom":false},{"permKey":"comment_limit_cycle_start","permValue":"23:00:00","permStatus":"","isCustom":false},{"permKey":"comment_limit_cycle_end","permValue":"08:30:00","permStatus":"","isCustom":false},{"permKey":"comment_limit_rule","permValue":1,"permStatus":"","isCustom":false},{"permKey":"post_editor_image","permValue":true,"permStatus":"","isCustom":false},{"permKey":"post_editor_video","permValue":true,"permStatus":"","isCustom":false},{"permKey":"post_editor_audio","permValue":true,"permStatus":"","isCustom":false},{"permKey":"post_editor_document","permValue":true,"permStatus":"","isCustom":false},{"permKey":"comment_editor_image","permValue":true,"permStatus":"","isCustom":false},{"permKey":"comment_editor_video","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_editor_audio","permValue":false,"permStatus":"","isCustom":false},{"permKey":"comment_editor_document","permValue":false,"permStatus":"","isCustom":false},{"permKey":"image_max_size","permValue":5,"permStatus":"","isCustom":false},{"permKey":"video_max_size","permValue":50,"permStatus":"","isCustom":false},{"permKey":"video_max_time","permValue":15,"permStatus":"","isCustom":false},{"permKey":"audio_max_size","permValue":50,"permStatus":"","isCustom":false},{"permKey":"audio_max_time","permValue":60,"permStatus":"","isCustom":false},{"permKey":"document_max_size","permValue":10,"permStatus":"","isCustom":false},{"permKey":"download_file_count","permValue":999,"permStatus":"","isCustom":false}]',
];
