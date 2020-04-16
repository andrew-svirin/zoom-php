<?php

namespace AndrewSvirin\Zoom\Tests;

use AndrewSvirin\Zoom\Requests\Meeting\CreateMeeting;
use AndrewSvirin\Zoom\Requests\Meeting\DeleteMeeting;
use AndrewSvirin\Zoom\Requests\User\CreateUser;
use AndrewSvirin\Zoom\Requests\User\DeleteUser;

class ZoomClientTest extends BaseTestCase
{

    /**
     * @group jwt
     */
    public function testGenerateJWT()
    {
        $generateJWTKey = $this->getMethod($this->apiClient, 'generateJWTKey');
        $jwtKey = $generateJWTKey->invokeArgs($this->apiClient, []);
        $this->assertNotEmpty($jwtKey);
    }

    /**
     * @group create-user
     */
    public function testUser()
    {
        try {
            $user = $this->createUser();
            $this->assertArrayHasKey('id', $user);
            $this->apiClient->call(new DeleteUser($user['id']));
        } catch (\Exception $exception) {
            $this->assertTrue(in_array($exception->getCode(), [1005, 1001]));
        }
    }

    /**
     * @group activate-user
     */
    public function testActivateUser()
    {
        try {
            $user = $this->createUser();
            $this->assertTrue($user['activate']);
            $this->apiClient->call(new DeleteUser($user['id']));
        } catch (\Exception $exception) {
            $this->assertTrue(in_array($exception->getCode(), [1005, 1001, 124]));
        }
    }

    /**
     * @group delete-user
     */
    public function testDeleteUser()
    {
        try {
            $user = $this->createUser();
            $delete = $this->apiClient->call(new DeleteUser($user['id']))->getJson();
            $this->assertEmpty($delete);
        } catch (\Exception $exception) {
            $this->assertTrue(in_array($exception->getCode(), [1005, 1001, 124]));
        }
    }

    /**
     * @group create-meeting
     */
    public function testCreateMeeting()
    {
        try {
            $user = $this->createUser();
            $meeting = $this->apiClient->call(new CreateMeeting($user['id'], [
                'topic' => 'Some Topic',
            ]))->getJson();
            $this->assertArrayHasKey('id', $meeting);
            $this->apiClient->call(new DeleteUser($user['id']));
        } catch (\Exception $exception) {
            $this->assertTrue(in_array($exception->getCode(), [1005, 1001, 124]));
        }
    }

    /**
     * @group delete-meeting
     */
    public function testDeleteMeeting()
    {
        try {
            $user = $this->createUser();
            $meeting = $this->apiClient->call(new CreateMeeting($user['id'], [
                'topic' => 'Some Topic',
            ]))->getJson();
            $delete = $this->apiClient->call(new DeleteMeeting($meeting['id']))->getJson();
            $this->assertEmpty($delete);
            $this->apiClient->call(new DeleteUser($user['id']));
        } catch (\Exception $exception) {
            $this->assertTrue(in_array($exception->getCode(), [1005, 1001, 124]));
        }
    }

    /**
     * @param string $email
     * @param bool $activate
     * @return array|string
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    private function createUser($email = null, $activate = true)
    {
        if (null == $email) {
            $email = $this->getEmail();
        }
        $password = '12345678aA';
        $firstName = 'Some';
        $lastName = 'Name';

        $user = $this->apiClient->call(new CreateUser($email, CreateUser::TYPE_BASIC, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => $password,
        ]))->getJson();

        if ($activate) {
            // Wait for email settle down in email box.
            $config = $this->getConfig();
            if (isset($config['integration']) && $config['integration']) {
                sleep(2);
            }
            $user['activate'] = $this->emailClient->activate($user['email'], $password, $firstName, $lastName);
        }
        return $user;
    }
}