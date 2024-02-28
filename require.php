<?php
$CONFIG = [
    'sticky' => [
        'endpoint' => "https://sandboxdemo.sticky.io/",
        'username' => "code_dev_api",
        'password' => "xNEHjxm49enRGM",
    ],
    'shipping_id' => 35,
    'tranType' => 'Sale',
    'currency' => 'USD',
    'product_mapp' =>
    [
        'offer_id' => '122',
        'products' =>
        [
            "product_details" =>
            [
                [
                    "skuid" => "retretre4545456",
                    "pid" => "969",
                    "cid" => "140",
                    "title" => "Apricots",
                    "shopify_pid" => "43020274172069",
                    "shopify_vid" => "43020274172069"
                    
                ]
            ]
        ],
    ],
    'shopify' => [
        'shopify_endpoint' => 'https://learnstoredev.myshopify.com',
        'X-Shopify-Access-Token' => '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',
        'api_key' => '098fefe69c1f36b9e7b9173510641ace',
        'api_ss' => 'af1b4417847d62f66fc91546ce55e2fd'
    ]
];

defined("CONFIG") || define("CONFIG", $CONFIG);