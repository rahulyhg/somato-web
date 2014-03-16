<?php

/*
|--------------------------------------------------------------------------
| Card Builder
|--------------------------------------------------------------------------
| Takes user info and a template and assembles a card
|
| Usage:
| $builder = new CardBuilder($card, $template);
| $builder->assemble();
| echo $builder->product; // String of SVG code with fully assembled card
|
*/

class CardBuilder
{
    private $card;
    private $template;

    public $product;

    public function __construct($card, $template)
    {
        $this->card = $card;
        $this->template = $template;
        $this->product = $this->template->map;
    }

    public function assemble()
    {
        // Replace all fields
        $this->replace('first_name');
        $this->replace('last_name');
        $this->replace('job_title');
        $this->replace('tagline');
        $this->replace('company');
        $this->replace('cell');
        $this->replace('office');
        $this->replace('email');
        $this->replace('web');
        $this->replace('mailing_address');
        $this->replace('street_address');
    }

    private function replace($field)
    {
        $this->product = str_replace('[['.$field.']]', $this->card->{$field}, $this->product);
    }
}