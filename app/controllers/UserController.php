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

            // Return the user object
            return Response::json(Auth::user());
        }
        else
        {
            // Authentication failed
            return "FAIL";
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
        if (Input::has('error'))
        {
            // Handle denied request
        }
        else
        {
            $input = Input::all();
            $linkedin = array(
                'apiKey' => Config::get('linkedin.apiKey'),
                'secretKey' => Config::get('linkedin.secretKey'),
                'redirectUri' => ,
                'code' => $input['code'],
                'state' => $input['state'],
                'desiredState' => 'u0NepzEM6SwgUYCq'
            );
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