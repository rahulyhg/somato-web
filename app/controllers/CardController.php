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

    /*
    |--------------------------------------------------------------------------
    | Make Default Card
    |--------------------------------------------------------------------------
    | Run on registration
    | Creates a default card for the user to have on first log in
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

    /*
    |--------------------------------------------------------------------------
    | Get current card
    |--------------------------------------------------------------------------
    | Get's a user's currently selected card
    */
    public function getCurrent($userId)
    {
        $card = Card::where('user_id', '=', $userId)
                    ->where('current', '=', true)
                    ->get();

        $user = User::find($userId);

        $template = Template::find($card->template_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Get card by id
    |--------------------------------------------------------------------------
    | Pull a card by it's id
    | Used to download another person's card
    */
    public function get($cardId)
    {

    }

}