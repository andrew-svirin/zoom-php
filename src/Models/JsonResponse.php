<?php

namespace AndrewSvirin\Zoom\Models;

use AndrewSvirin\Zoom\Exceptions\ZoomException;
use GuzzleHttp\Psr7\Response;

/**
 * Guzzle Json Response.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class JsonResponse extends Response
{

    public function __construct($status, $headers, $body, $version, $reason)
    {
        parent::__construct($status, $headers, $body, $version, $reason);
    }

    /**
     * Get json decoded data.
     * @throws ZoomException
     */
    public function getJson(): array
    {
        // Get parent Body stream.
        $body = $this->getBody();

        // If JSON HTTP header was not detected - then throw error.
        if (false === strpos($this->getHeaderLine('Content-Type'), 'application/json')) {
            throw new ZoomException('Incorrect header was specified');
        }
        return json_decode($body, true);
    }
}
