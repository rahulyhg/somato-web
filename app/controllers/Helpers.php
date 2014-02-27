<?php

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
| Any useful functions that are used somewhere in the app
*/

class Helpers {

    /*
    |--------------------------------------------------------------------------
    | Generate Access Key
    |--------------------------------------------------------------------------
    | Require:  none
    | Action:   generate a new access key to allow API usage by app
    | Return:   new char access key string
    */
    public static function generateAccessKey($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $random;
    }

}