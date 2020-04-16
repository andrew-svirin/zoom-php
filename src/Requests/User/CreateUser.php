<?php

namespace AndrewSvirin\Zoom\Requests\User;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * A Zoom account can have one or more users.
 * Use this Request to add a new user to your account.
 * Also this method will response added account.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/users/usercreate
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class CreateUser extends Request
{

    const TYPE_BASIC = 1;

    public function __construct($email, $type = self::TYPE_BASIC, array $options = [])
    {
        $this->method = 'POST';
        $this->uri = 'v2/users';
        $this->json = [
            'action' => 'create',
            'user_info' => array_merge($options, [
                'email' => $email,
                'type' => $type,
            ]),
        ];
    }
}