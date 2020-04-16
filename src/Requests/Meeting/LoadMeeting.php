<?php

namespace AndrewSvirin\Zoom\Requests\Meeting;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * Get Meeting information.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meeting
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class LoadMeeting extends Request
{

    public function __construct($id)
    {
        $this->method = 'GET';
        $this->uri = sprintf('v2/meetings/%s', $id);
    }
}