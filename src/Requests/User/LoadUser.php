<?php

namespace AndrewSvirin\Zoom\Requests\User;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * Get User information.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/users/user
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class LoadUser extends Request
{

    public function __construct($id)
    {
        $this->method = 'GET';
        $this->uri = sprintf('v2/users/%s', $id);
    }
}