<?php

namespace AndrewSvirin\Zoom\Tests\Data;

class GuzzleMock extends \GuzzleHttp\Handler\MockHandler
{

    private $config = [];
    private $callbacks = [];

    public function __construct(array $config, array $callbacks, array $queue = null, callable $onFulfilled = null, callable $onRejected = null)
    {
        $this->config = $config;
        $this->callbacks = $callbacks;
        parent::__construct($queue, $onFulfilled, $onRejected);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $req
     * @param array $options
     * @return \GuzzleHttp\Promise\FulfilledPromise|\GuzzleHttp\Promise\PromiseInterface
     * @throws \Exception
     */
    public function __invoke(\Psr\Http\Message\RequestInterface $req, array $options)
    {
        $path = $req->getUri()->getPath();
        $method = $req->getMethod();

        if (!isset($this->callbacks[$path][strtoupper($method)])) {
            throw new \Exception(sprintf('Callback %s %s was not specified in callbacks().', $path, strtoupper($method)));
        }

        $headers = ['Content-Type' => 'application/json'];
        $response = new \GuzzleHttp\Psr7\Response(200, $headers, json_encode($this->callbacks[$path][strtoupper($method)]));
        $promise = new \GuzzleHttp\Promise\Promise();
        $promise->resolve($response);
        return $promise;
    }
}