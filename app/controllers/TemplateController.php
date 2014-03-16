<?php

/*
|--------------------------------------------------------------------------
| Template Controller
|--------------------------------------------------------------------------
| Create new themes (web app)
| Edit themes, fork themes
*/

class TemplateController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Template creator
    |--------------------------------------------------------------------------
    | Create new theme or resume from old
    */
    public function creator($themeId = 0)
    {
        return View::make('templates.creator', array('themeId' => $themeId));
    }

    /*
    |--------------------------------------------------------------------------
    | Save a theme
    |--------------------------------------------------------------------------
    | Save new theme in db or update old
    */
    public function save()
    {
        $post = Input::all();
    }

}