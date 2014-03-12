<?php

class CardController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function makeDefaultCard($userId)
    {
        // Get user
        $user = User::find($userId);

        // Create the card
        $card = new Card;
        $card->user_id = $userId;
        $card->current = true;
        $card->template_id = 1;
        $card->email = $user->email;

        $card->save();
    }

}