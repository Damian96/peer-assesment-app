<?php

return [
    /**
     * The administrators emails
     */
    'mails' => [
        'admin' => [
            'from' => 'Administrator',
            'address' => 'damgiankakis@gmail.com',
        ],
        'support' => [
            'from' => 'Support',
            'address' => 'support@peerassess.test',
        ],
    ],

    /**
     * Some predefined date formats
     */
    'date' => [
        'full' => DATE_COOKIE,
        'stamp' => 'Y-m-d H:i:s'
    ],
//    'invite' => [
//        'subject' => 'You are enrolled into',
//    ]
];
