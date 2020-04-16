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
     * URI where send request.
     * @var string
     */
    protected $uri;

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

    public function hasJson(): bool
    {
        return !empty($this->json);
    }

    public function getJson(): ?array
    {
        return $this->json;
    }

    public function getURI(): string
    {
        return $this->uri;
    }
}
