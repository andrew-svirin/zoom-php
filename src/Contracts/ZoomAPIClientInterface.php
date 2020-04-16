<?php

namespace AndrewSvirin\Zoom\Contracts;

use AndrewSvirin\Zoom\Requests\Request;

/**
 * ZOOM API client interface.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
interface ZoomAPIClientInterface
{

    /**
     * Call prepared Requests from Zoom service.
     * @param Request $request
     * @return \AndrewSvirin\Zoom\Models\JsonResponse|\Psr\Http\Message\ResponseInterface
     */
    public function call(Request $request);
}