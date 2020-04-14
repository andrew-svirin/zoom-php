<?php

namespace AndrewSvirin\Zoom\Requests;

/**
 * Request class.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
abstract class Request
{

    /**
     * One of HTTP method.
     * @var string
     */
    protected $method;

    /**
     * Query parameters
     * @var array
     */
    protected $parameters = [];

    /**
     * Json array for post data.
     * @var array
     */
    protected $json;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getJson(): ?array
    {
        return $this->json;
    }
}
