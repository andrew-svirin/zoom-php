<?php

namespace AndrewSvirin\Zoom\Requests;

/**
 * A Zoom account can have one or more users.
 * Use this Request to add a new user to your account.
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
        $this->json = [
            'email' => $email,
            'type' => $type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => $password,
        ];
    }
}