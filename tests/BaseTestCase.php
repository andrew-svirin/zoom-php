<?php

namespace AndrewSvirin\Zoom\Tests;

abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \AndrewSvirin\Zoom\ZoomAPIClient
     */
    protected $apiClient;

    /**
     * @var \AndrewSvirin\Zoom\ZoomEmailClient
     */
    protected $emailClient;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Put env.php file in Data folder.
     * @return array
     */
    protected function getConfig(): array
    {
        $envPath = __DIR__ . '/Data/env.php';
        if (!file_exists($envPath)) {
            {
                return include(__DIR__ . '/Data/env.default.php');
            }
        }
        return include(__DIR__ . '/Data/env.php');
    }

    /**
     * Get Account Email.
     */
    protected function getEmail()
    {
        static $email = null;
        if (null === $email) {
            $email = $this->emailClient->create(rand(100, 777));
        }
        return $email;
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
                    'id' => 'some-user-id',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => $this->getEmail(),
                    'type' => 1,
                ],
            ],
            sprintf('/v2/users/%s', 'some-user-id') => [
                'DELETE' => '',
            ],
            sprintf('/v2/users/%s/meetings', 'some-user-id') => [
                'POST' => [
                    'uuid' => 'some_uuid_b64',
                    'id' => 'some-meeting-id',
                    'host_id' => 'some_id',
                    'topic' => 'Some Topic',
                    'type' => 2,
                    'status' => 'waiting',
                    'start_time' => date('Y-m-dTH:i:sZ'),
                    'duration' => 60,
                    'timezone' => 'America/Los_Angeles',
                    'created_at' => date('Y-m-dTH:i:sZ'),
                    'start_url' => 'https://zoom.us/s/123?zak=some-hash',
                    'join_url' => 'https://zoom.us/j/123',
                    'settings' =>
                        [
                            'host_video' => false,
                            'participant_video' => false,
                            'cn_meeting' => false,
                            'in_meeting' => false,
                            'join_before_host' => false,
                            'mute_upon_entry' => false,
                            'watermark' => false,
                            'use_pmi' => false,
                            'approval_type' => 2,
                            'audio' => 'both',
                            'auto_recording' => 'none',
                            'enforce_login' => false,
                            'enforce_login_domains' => '',
                            'alternative_hosts' => '',
                            'close_registration' => false,
                            'registrants_confirmation_email' => true,
                            'waiting_room' => true,
                            'global_dial_in_countries' =>
                                [
                                    'US',
                                ],
                            'global_dial_in_numbers' =>
                                [
                                    [
                                        'country_name' => 'US',
                                        'city' => 'New York',
                                        'number' => '+1 6465588656',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                    [
                                        'country_name' => 'US',
                                        'city' => 'San Jose',
                                        'number' => '+1 6699009128',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                    [
                                        'country_name' => 'US',
                                        'city' => '',
                                        'number' => '+1 2532158782',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                    [
                                        'country_name' => 'US',
                                        'city' => '',
                                        'number' => '+1 3017158592',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                    [
                                        'country_name' => 'US',
                                        'city' => 'Chicago',
                                        'number' => '+1 3126266799',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                    [
                                        'country_name' => 'US',
                                        'city' => 'Houston',
                                        'number' => '+1 3462487799',
                                        'type' => 'toll',
                                        'country' => 'US',
                                    ],
                                ],
                            'registrants_email_notification' => true,
                            'meeting_authentication' => false,
                        ],
                ],
            ],
            sprintf('/v2/meetings/%s', 'some-meeting-id') => [
                'DELETE' => '',
            ],
        ];
    }

    /**
     * Simulate client request responses.
     */
    protected function setUp(): void
    {
        $config = $this->getConfig();
        $httpClient = new \GuzzleHttp\Client();
        $imapClient = new \AndrewSvirin\Zoom\IMAPClient();
        $this->apiClient = new \AndrewSvirin\Zoom\ZoomAPIClient($httpClient, $config['api']);
        $this->emailClient = new \AndrewSvirin\Zoom\ZoomEmailClient($httpClient, $imapClient, $config['email']);
        if (!isset($config['integration']) || !$config['integration']) {
            $callbacks = $this->getCallbacks();
            // Create a mock and queue two responses.
            $mock = new \AndrewSvirin\Zoom\Tests\Data\GuzzleMock($callbacks);
            $handlerStack = \GuzzleHttp\HandlerStack::create($mock);
            $httpClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
            $this->setProtectedProperty($this->apiClient, 'httpClient', $httpClient);
            $this->emailClient = \Mockery::mock(\AndrewSvirin\Zoom\ZoomEmailClient::class)->makePartial();
            $this->emailClient->shouldReceive('activate')->andReturn(true);
        }
    }

    protected function setProtectedProperty($class, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($class);
        $channelProperty = $reflectionClass->getProperty($propertyName);
        $channelProperty->setAccessible(true);
        $channelProperty->setValue($class, $value);
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