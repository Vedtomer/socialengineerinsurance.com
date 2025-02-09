<?php

return [
    'meta' => [
        'defaults'       => [
            'title'        => 'Social Engineer Insurance | E-Rikshaw Insurance',
            'titleBefore'  => false,
            'description'  => 'Specialists in E-Rikshaw Insurance and All Types of Insurance Services, including Health and Motor Vehicle Insurance',
            'separator'    => ' - ',
            'keywords'     => ['insurance', 'e-rickshaw insurance', 'health insurance', 'motor insurance'],
            'canonical'    => null,
            'robots'       => false,
        ],
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => 'Social Engineer Insurance | E-Rikshaw Insurance',
            'description' => 'Specialists in E-Rikshaw Insurance and All Types of Insurance Services, including Health and Motor Vehicle Insurance',
            'url'         => null,
            'type'        => 'website',
            'site_name'   => 'Social Engineer Insurance',
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card'        => 'summary',
            'site'        => '@socialengineerinsurance',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'url'      => null,
            'name'     => 'Social Engineer Insurance | E-Rikshaw Insurance',
            'description' => 'Specialists in E-Rikshaw Insurance and All Types of Insurance Services, including Health and Motor Vehicle Insurance',
        ],
    ],
];
