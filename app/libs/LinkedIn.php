<?php

/*
|--------------------------------------------------------------------------
| Linked API
|--------------------------------------------------------------------------
| Author: Paul Dilyard
| Fetch user profile information from LinkedIn
|--------------------------------------------------------------------------
| Usage:
|
| $linkedin = array(
|     'apiKey' => YOUR_API_KEY,
|     'secretKey' => YOUR_SECRET_KEY,
|     'redirectUri' => CURRENT_APP_URL,
|     'code' => AUTHORIZATION_CODE,
|     'state' => STATE,
|     'desiredState' => STATE_TO_CHECK_AGAINST
| );
| // Get apiKey and secretKey from developer console
| // Redirect URI must be the URI where you use this library
| // code & state are provided by linkedin API
| // desiredState is the string to prevent CSRF
|
| $user = new LinkedIn($linkedin);
|
| if ($user->checkState())
| {
|     $user->getProfile();
|     echo $user->email; // outputs email address
| }
*/

class LinkedIn {

    /*
    |--------------------------------------------------------------------------
    | Available User Data
    |--------------------------------------------------------------------------
    */
    public $accessToken;
    public $email;
    public $firstName;
    public $lastName;
    public $headline;
    public $positions;
    public $address;
    public $phones;

    /*
    |--------------------------------------------------------------------------
    | Default Constructor
    |--------------------------------------------------------------------------
    | Params:  authorization_code, state, desired state, redirectUri
    | Action:  sets object access key
    */
    public function __construct($info)
    {
        $this->apiKey = $info['apiKey'];
        $this->secretKey = $info['secretKey'];
        $this->redirectUri = $info['redirectUri'];
        $this->code = $info['code'];
        $this->state = $info['state'];
        $this->desiredState = $info['desiredState'];
    }

    /*
    |--------------------------------------------------------------------------
    | Check State
    |--------------------------------------------------------------------------
    | Action:  prevents CSRF
    */
    public function checkState()
    {
        if ($this->code == $this->desiredState)
            return true;
        else
            return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Get User Profile
    |--------------------------------------------------------------------------
    */
    public function getProfile()
    {
        $this->getAccessToken();
        $this->getProfileOverview();
        $this->getEmail();
    }

    /*
    |--------------------------------------------------------------------------
    | Get an access token
    |--------------------------------------------------------------------------
    */
    private function getAccessToken()
    {
        // Assemble URL
        $url = $LINKEDIN_BASE_URL .
               "accessToken" .
               "?grant_type=authorization_code" .
               "&code=" . $this->code .
               "&redirect_uri=" . $this->redirectUri .
               "&client_id=" . $this->apiKey .
               "&client_secret=" . $this->secretKey;

        // Make request
        $curl = new Curl;
        $response = $curl->simple_get($url);

        // Parse JSON to get access_token
        $data = json_decode($response);

        // Set access token
        $this->accessToken = $data->access_token;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Profile Overview
    |--------------------------------------------------------------------------
    */
    private function getProfileOverview()
    {
        // Make API Request
        $url = $API_BASE_URL .
               "people/~" .
               "?oauth2_access_token=" . $this->accessToken;

        $curl = new Curl;
        $response = $curl->simple_get($url);

        // Parse XML
        $person = new SimpleXMLElement($response);
        $person = $person->person;

        $this->firstName = $person->{'first-name'};
        $this->lastName = $person->{'last-name'};
        $this->headline = $person->headline;
        $this->positions = $person->positions;
        $this->address = $person->{'main-address'};
        $this->phones = $person->{'phone-numbers'};
    }

    /*
    |--------------------------------------------------------------------------
    | Get Email Address
    |--------------------------------------------------------------------------
    */
    public function getEmail()
    {
        // Make API request
        $url = $API_BASE_URL .
               "people/~/email-address" .
               "?oauth2_access_token=" . $this->accessToken;

        $curl = new Curl;
        $response = $curl->simple_get($url);

        $this->email = $response;
    }

    private static $LINKEDIN_BASE_URL = "https://www.linkedin.com/uas/oauth2/";
    private static $API_BASE_URL = "https://api.linkedin.com/v1/";
    private $code;
    private $state;
    private $desiredState;
    private $redirectUri;
    private $apiKey;
    private $secretKey;
}