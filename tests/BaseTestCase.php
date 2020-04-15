<?php

namespace AndrewSvirin\Zoom\Tests;

abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \AndrewSvirin\Zoom\ZoomClient
     */
    protected $client;

    /**
     * Put env.php file in Data folder.
     * @return array
     */
    protected function getConfig(): array
    {
        $envPath = __DIR__ . '/Data/env.php';
        if (!file_exists($envPath)) {
            return [
                'integration' => false,
                'url' => 'https://api.zoom.us',
                'jwt' => [
                    'api_key' => '123',
                    'api_secret' => 'abc',
                ],
            ];
        }
        return include(__DIR__ . '/Data/env.php');
    }

    /**
     * Simulated callbacks.
     * @return array
     */
    protected function getCallbacks()
    {
        return [
            '/v2/users' => [
                'POST' => [
                    'id' => '-zTkKyS4Q82OFr8Jjec8-w',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => 'some-fake-email@fake.domain',
                    'type' => 1,
                ],
            ],
        ];
    }

    /**
     * Simulate client request responses.
     */
    protected function setUp(): void
    {
        $config = $this->getConfig();
        if (isset($config['integration']) && $config['integration']) {
            $client = new \GuzzleHttp\Client();
        } else {
            $callbacks = $this->getCallbacks();
            // Create a mock and queue two responses.
            $mock = new \AndrewSvirin\Zoom\Tests\Data\GuzzleMock($config, $callbacks);
            $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
            $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);
        }
        $this->client = new \AndrewSvirin\Zoom\ZoomClient($client, $config);
    }

    protected function setProtectedProperty($class, $mock, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($class);
        $channelProperty = $reflectionClass->getProperty($propertyName);
        $channelProperty->setAccessible(true);
        $channelProperty->setValue($mock, $value);
        $channelProperty->setAccessible(false);
    }

    protected static function getMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}