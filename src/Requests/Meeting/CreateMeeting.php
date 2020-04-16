<?php

namespace AndrewSvirin\Zoom\Requests\Meeting;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * Create Meeting for a user.
 * @see https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingcreate
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class CreateMeeting extends Request
{

    const TYPE_INSTANT_MEETING = 1;
    const TYPE_SCHEDULED_MEETING = 2;

    public function __construct($userId, array $options = [])
    {
        $this->method = 'POST';
        $this->uri = sprintf('v2/users/%s/meetings', $userId);
        $this->json = $options;
    }
}