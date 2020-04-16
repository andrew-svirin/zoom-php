<?php

return [
    'integration' => false, // toggle to true for make real requests to Zoom.
    'api' => [
        'url' => 'https://api.zoom.us',
        'jwt' => [
            'api_key' => '123',
            'api_secret' => 'abc',
        ],
    ],
    'email' => [
        'mailbox' => '{imap.gmail.com:993/imap/ssl}INBOX',
        'account' => [
            'email' => 'some@email', // use some+1@email email for handle domain of accounts emails.
            'password' => 'pass',
        ],
    ],
];