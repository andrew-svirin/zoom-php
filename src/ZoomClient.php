<?php

namespace AndrewSvirin\Zoom;

use AndrewSvirin\Zoom\Contracts\ZoomClientInterface;
use AndrewSvirin\Zoom\Requests\Request;

/**
 * ZOOM client representation.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
final class ZoomClient implements ZoomClientInterface
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function call(Request $request)
    {

    }
}

