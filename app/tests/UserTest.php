<?php

class UserTest extends TestCase {

    /*
    |--------------------------------------------------------------------------
    | Test Registration
    |--------------------------------------------------------------------------
    */
    public function testRegistration()
    {
        $params = array(
            'first_name' => 'Paul',
            'last_name' => 'Dilyard',
            'email' => 'paul.dilyard@gmail.com',
            'password' => 'password',
            'network' => 'somato'
        );
        $response = $this->call('POST', 'user/register', $params);

        echo $response->getContent();
        $this->assertTrue( is_string($response->getContent()) );
    }

    /*
    |--------------------------------------------------------------------------
    | Test Login
    |--------------------------------------------------------------------------
    */
    /* Commented out to keep access_key from changing
    public function testLogin()
    {
        $params = array(
            'email' => 'paul.dilyard@gmail.com',
            'password' => 'password'
        );
        $response = $this->call('POST', 'user/login', $params);

        echo $response->getContent();
        $this->assertTrue( is_string($response->getContent()) );
    }
    /*

    /*
    |--------------------------------------------------------------------------
    | Test Login Failure
    |--------------------------------------------------------------------------
    */
    public function testLoginFailure()
    {
        $params = array(
            'email' => 'aegergg',
            'password' => 'aewgerg'
        );
        $response = $this->call('POST', 'user/login', $params);

        $this->assertTrue($response->getContent() == "FAIL");
    }

    /*
    |--------------------------------------------------------------------------
    | Test Get User
    |--------------------------------------------------------------------------
    */
    public function testGetUser()
    {
        $params = array(
            'access_key' => 'EuBLsPls3dgGDIHNyRe5BvHFCAWFrLv6'
        );
        $response = $this->call('GET', 'user/1', $params);

        echo $response->getContent();
        $this->assertTrue( is_string($response->getContent()) );
    }

}