<?php

/*
|--------------------------------------------------------------------------
| ShareController
|--------------------------------------------------------------------------
|
| Algorithms to obtain list of nearby users
|
*/

class ShareController extends BaseController
{
    public function wifi()
    {
        $input = Input::only('device_list');

        $deviceList = json_decode($input['device_list']);
    }
}