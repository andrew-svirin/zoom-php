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
    public function testCreateUser()
    {
        try {
            $user = $this->createUser($this->getEmail(), false);
            $this->assertArrayHasKey('id', $user);
        } catch (\Exception $exception) {
            $this->assertEquals(1005, $exception->getCode());
            $this->assertEquals(sprintf('User already in the account: %s', $this->getEmail()), $exception->getMessage());
        }
    }

    /**
     * @group activate-user
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    public function testActivateUser()
    {
        $user = $this->createUser();
        $this->assertTrue($user['activate']);
        $this->apiClient->call(new DeleteUser($user['id']));
    }

    /**
     * @group delete-user
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    public function testDeleteUser()
    {
        $user = $this->createUser();
        try {
            $delete = $this->apiClient->call(new DeleteUser($user['id']))->getJson();
            $this->assertEmpty($delete);
        } catch (\Exception $exception) {
            $this->assertEquals(1001, $exception->getCode());
            $this->assertEquals(sprintf('User %s not exist or not belong to this account.', $user['email']), $exception->getMessage());
        }
    }

    /**
     * @group create-meeting
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    public function testCreateMeeting()
    {
        $user = $this->createUser();
        $meeting = $this->apiClient->call(new CreateMeeting($user['id'], [
            'topic' => 'Some Topic',
        ]))->getJson();
        $this->assertArrayHasKey('id', $meeting);
        $this->apiClient->call(new DeleteUser($user['id']));
    }

    /**
     * @group delete-meeting
     * @throws \AndrewSvirin\Zoom\Exceptions\ZoomException
     */
    public function testDeleteMeeting()
    {
        $user = $this->createUser();
        $meeting = $this->apiClient->call(new CreateMeeting($user['id'], [
            'topic' => 'Some Topic',
        ]))->getJson();
        $delete = $this->apiClient->call(new DeleteMeeting($meeting['id']))->getJson();
        $this->assertEmpty($delete);
        $this->apiClient->call(new DeleteUser($user['id']));
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