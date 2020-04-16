# Zoom-php V2

Based on Zoom API https://marketplace.zoom.us/docs/api-reference/zoom-api

# Usage

```php
// Configuration
$configuration = [
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
```

```php
// Goal is to creat meeting in Zoom by new invited zoom account.
// Make domain email. That can be reached from general email via IMAP
$email = $this->emailClient->create(rand(100, 777));
$password = '12345678aA';
$firstName = 'Some';
$lastName = 'Name';

// Invite user in Zoom.
$user = $this->apiClient->call(new \AndrewSvirin\Zoom\Requests\User\CreateUser(
    $email, 
    \AndrewSvirin\Zoom\Requests\User\CreateUser::TYPE_BASIC,
    [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'password' => $password,
    ]
))->getJson();

// Wait for email settle down in email box.
sleep(2);
// Activate email in Zoom.
$activate = $this->emailClient->activate($user['email'], $password, $firstName, $lastName);

// Create meeting by activated Zoom account.
$meeting = $this->apiClient->call(new \AndrewSvirin\Zoom\Requests\Meeting\CreateMeeting($user['id'], [
    'topic' => 'Some Topic',
]))->getJson();
```

### Statistic
[![Build Status](https://travis-ci.org/andrew-svirin/zoom-php.svg?branch=master)](https://travis-ci.org/andrew-svirin/zoom-php)