<?php

namespace AndrewSvirin\Zoom\Requests\User;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * Deleting a user permanently removes them and their data from Zoom.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/users/userdelete
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class DeleteUser extends Request
{

    /**
     * DeleteUser constructor.
     * @param string $id User Id or Email.
     */
    public function __construct($id)
    {
        $this->method = 'DELETE';
        $this->uri = sprintf('v2/users/%s', $id);
        $this->parameters = [
            'action' => 'delete',
        ];
    }
}