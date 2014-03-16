<?php

/*
|--------------------------------------------------------------------------
| Card Controller
|--------------------------------------------------------------------------
| Create, update, delete, cards
*/

class CardController extends BaseController {

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
        // Get the user's current card
        $card = Card::where('user_id', '=', $userId)
                    ->where('current', '=', true)
                    ->first();

        // Get the user
        $user = User::find($userId);

        // Add user info to card
        $card->first_name = $user->first_name;
        $card->last_name = $user->last_name;

        // Get the template
        $template = Template::find($card->template_id);

        // Put the card data into the template
        $builder = new CardBuilder($card, $template);
        $builder->assemble();

        // Write the card to a file
        $file = fopen('cards/'.$card->id.'.svg', 'w');
        fwrite($file, $builder->product);
        fclose($file);

        // Show the card
        return View::make('cards.card', array('svg' => $card->id));
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
        // Get the card
        $card = Card::find($cardId);

        // Add user info
        $user = User::find($card->user_id);
        $card->first_name = $user->first_name;
        $card->last_name = $user->last_name;

        // Get the template
        $template = Template::find($card->template_id);

        // Build the card
        $builder = new CardBuilder($card, $template);
        $builder->assemble();

        // Write the card to a file
        $file = fopen('cards/'.$card->id.'.svg', 'w');
        fwrite($file, $builder->product);
        fclose($file);

        // Show the card
        return View::make('cards.card', array('svg' => $card->id));
    }

}