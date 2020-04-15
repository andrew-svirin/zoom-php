<?php

namespace AndrewSvirin\Zoom\Tests;

use AndrewSvirin\Zoom\Requests\User\CreateUser;

class ZoomClientTest extends BaseTestCase
{

    /**
     * @group jwt
     */
    public function testGenerateJWT()
    {
        $generateJWTKey = $this->getMethod($this->client, 'generateJWTKey');
        $jwtKey = $generateJWTKey->invokeArgs($this->client, []);
        $this->assertNotEmpty($jwtKey);
    }

    /**
     * @group create-user
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    public function testCreateUser()
    {
        $response = $this->client->call(new CreateUser('some-fake-email@fake.domain'));
        $response->getStatusCode();
        $json = $response->getJson();
        $this->assertArrayHasKey('id', $json);
    }
}