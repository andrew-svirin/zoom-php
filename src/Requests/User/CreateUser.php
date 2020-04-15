<?php

namespace AndrewSvirin\Zoom\Requests\User;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * A Zoom account can have one or more users.
 * Use this Request to add a new user to your account.
 * Also this method will response added account.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class CreateUser extends Request
{

    const TYPE_BASIC = 1;

    public function __construct($email, $type = self::TYPE_BASIC, $first_name = null, $last_name = null, $password = null)
    {
        $this->method = 'POST';
        $this->uri = 'v2/users';
        $this->json = [
            'action' => 'create',
            'user_info' => [
                'email' => $email,
                'type' => $type,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => $password,
            ],
        ];
    }
}