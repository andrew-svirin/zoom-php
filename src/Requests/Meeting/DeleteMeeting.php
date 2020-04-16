<?php

namespace AndrewSvirin\Zoom\Requests\Meeting;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * Deleting a scheduled meeting.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingdelete
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class DeleteMeeting extends Request
{

    /**
     * DeleteMeeting constructor.
     * @param string $id Meeting Id.
     */
    public function __construct($id)
    {
        $this->method = 'DELETE';
        $this->uri = sprintf('v2/meetings/%s', $id);
    }
}