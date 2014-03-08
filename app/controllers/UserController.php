<?php

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
|
| Register, login, logout, update, linkedin, getUser
|
*/

class UserController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Register a new user
    |--------------------------------------------------------------------------
    | Require:  first_name, last_name, email, password, network
    | Action:   generates access key and stores new user in DB
    | Return:   user_id, access_key (both 0 on failure)
    */
    public function register()
    {
        // Retreive post data
        $info = Input::only('first_name', 'last_name', 'email', 'password', 'network');

        // Hash the password
        $info['password'] = Hash::make($info['password']);

        // Create an access key
        $accessKey = Helpers::generateAccessKey();

        // Create the new user
        $user = new User;
        $user->access_key = $accessKey;
        $user->first_name = $info['first_name'];
        $user->last_name = $info['last_name'];
        $user->email = $info['email'];
        $user->password = $info['password'];
        $user->network = $info['network'];

        try
        {
            $user->save();
        }
        catch (Exception $e)
        {
            $user->id = 0;
            $user->access_key = 0;
        }

        // Return JSON string of id and access_key
        return Response::json(array(
            'id' => $user->id,
            'accessKey' => $user->access_key
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Login a user
    |--------------------------------------------------------------------------
    | Require:  email, password
    | Action:   Retrieve user, update access key
    | Return:   all user info || "FAIL"
    */
    public function login()
    {
        $creds = Input::only('email', 'password');

        if(Auth::once($creds))
        {
            // Create a new access key
            $newAccessKey = Helpers::generateAccessKey();

            // Save the new access key
            $user = User::find(Auth::user()->id);
            $user->access_key = $newAccessKey;
            $user->save();

            // Create array of user info
            $userInfo = array(
                'id' => Auth::user()->id,
                'accessKey' => $newAccessKey,
                'success' => true
            );

            // Return the user object
            return Response::json($userInfo);
        }
        else
        {
            // Authentication failed
            $fail = array('success' => false);
            return Response::json($fail);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Log a user out
    |--------------------------------------------------------------------------
    | Require:  user_id, access_key
    | Action:   clear access key from user
    | Return:   "SUCCESS" || "FAIL"
    */
    public function logout()
    {
        $input = Input::only('user_id', 'access_key');

        $user = User::find($input['user_id']);
        if ($user->checkAccessKey($input['access_key']))
        {
            $user->access_key = '';
            $user->save();

            return "SUCCESS";
        }
        else
        {
            return "FAIL";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update a user profile
    |--------------------------------------------------------------------------
    | Require:  user_id, access_key, first_name, last_name, email
    | Action:   confirm access key and then update user info
    | Return:   new user info || "FAIL"
    */
    public function update()
    {
        $input = Input::only('user_id', 'access_key', 'first_name', 'last_name', 'email');

        $user = User::find($input['user_id']);

        if ($user->checkAccessKey($input['access_key']))
        {
            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->email = $input['email'];
            $user->save();

            return Response::json($user);
        }
        else
        {
            return "FAIL";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Register or login a user with LinkedIn
    |--------------------------------------------------------------------------
    | Require:  code, state
    | Action:   anti-CSRF, get and store access_token, get linkedin profile,
    |           store profile info in DB
    | Return:   all user info
    */
    public function linkedin()
    {
        if (Input::has('error') || !Input::has('code'))
        {
            /**
            * @todo
            * Handle denied request
            */
        }
        else
        {
            // Set up API library
            $input = Input::all();
            $linkedin = array(
                'apiKey' => Config::get('app.linkedin.apiKey'),
                'secretKey' => Config::get('app.linkedin.secretKey'),
                'redirectUri' => 'http://www.somatocards.com/user/linkedin',
                'code' => $input['code'],
                'state' => $input['state'],
                'desiredState' => Config::get('app.linkedin.desiredState')
            );

            $user = new Linkedin($linkedin);

            if ($user->checkState)
            {
                $user->getProfile();

                // Save the new user
                $newUser = new User;
                $newUser->access_key = $user->accessToken;
                $newUser->first_name = $user->firstName;
                $newUser->last_name = $user->lastName;
                $newUser->email = $user->email;
                $newUser->network = 'linkedin';

                try
                {
                    $newUser->save();
                }
                catch (Exception $e)
                {
                    $newUser->id = 0;
                    $newUser->access_key = 0;
                }

                /**
                * @todo
                * Login linkedin user (Cloud Messaging?)
                * Show success view
                */
            }
            else
            {
                /**
                * @todo
                * Handle CSRF
                */
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Get a user by ID
    |--------------------------------------------------------------------------
    | Require:  access_key
    | Params:   user_id
    | Action:   retrieve user from DB
    | Return:   all user info || "FAIL"
    */
    public function getUser($userId)
    {
        $input = Input::only('access_key');

        try
        {
            $user = User::findOrFail($userId);

            if ($user->checkAccessKey($input['access_key']))
            {
                return Response::json($user);
            }
            else
            {
                return "FAIL";
            }
        }
        catch (Exception $e)
        {
            return "FAIL";
        }
    }

}