<?php

use Core\Router;
use Helpers\Hooks;
use Helpers\Url;

/** Define routes. */

//Help
Router::any('create/channel', 'Controllers\Site@create_channel');

Router::any('privacy-policy', 'Controllers\Site@privacy');
Router::any('data-deletion', 'Controllers\Site@data_deletion');
Router::any('refund-policy', 'Controllers\Site@refund');

//AJAX
Router::any('ajax/subscribe/(:num)', 'Controllers\Ajax@subscribe');
Router::any('ajax/un_subscribe/(:num)', 'Controllers\Ajax@un_subscribe');

Router::any('ajax/like/(:num)', 'Controllers\Ajax@like');
Router::any('ajax/remove_like/(:num)', 'Controllers\Ajax@remove_like');

Router::any('ajax/dislike/(:num)', 'Controllers\Ajax@dislike');
Router::any('ajax/remove_dislike/(:num)', 'Controllers\Ajax@remove_dislike');

Router::any('ajax/search/(:any)', 'Controllers\Ajax@search');
Router::any('ajax/countries', 'Controllers\Ajax@countryList');


//SEO


Router::any('/', 'Controllers\Site@index');
Router::any('login', 'Controllers\Site@login');
Router::any('login/(:any)', 'Controllers\Site@login');
Router::any('register', 'Controllers\Site@register');
Router::any('register/(:any)', 'Controllers\Site@register');

//Authorization
Router::any('auth/google', 'Controllers\Auth@google');
Router::any('auth/facebook', 'Controllers\Auth@facebook_login');
Router::any('auth/facebook/callback', 'Controllers\Auth@facebook_callback');


Router::any('news', 'Controllers\Site@news');

Router::any('city/(:num)/(:any)', 'Controllers\Site@city');


Router::any('cat/(:any)', 'Controllers\Site@cat');
Router::any('tags/(:num)/(:any)', 'Controllers\Site@tag_cat');
Router::any('tags/(:any)', 'Controllers\Site@tags');


Router::any('user_panel/logout', 'Controllers\UserPanel@logout');
Router::any('user_panel/profile', 'Controllers\UserPanel@profile');

Router::any('set/region/(:any)', 'Controllers\Settings@region');

// Crons
Router::any('crons/coronavirus', 'Controllers\Cron@coronavirus');

// Channel ratings
Router::any('rating/channels', 'Controllers\Rating@channels');
Router::any('rating/news', 'Controllers\Rating@news');
Router::any('sitemap-update', 'Controllers\Sitemap@update');

Router::any('valyuta', 'Controllers\CurrencyController@index');
Router::any('namaz-vaxti', 'Controllers\NamazTimesController@index');
Router::any('hava-haqqinda/(:any)', 'Controllers\WeatherController@city');
Router::any('hava-haqqinda', 'Controllers\WeatherController@index');
Router::any('hava-haqqinda/', 'Controllers\WeatherController@index');



// NEWS INNER ROUTE START
    // slug -> channel_name/title_slug
    $currentURI = $_SERVER["REQUEST_URI"];
    $textsToCheck = array("/partner/", "/another-text/");

    $containsText = false;

    foreach ($textsToCheck as $text) {
        if (strpos($currentURI, $text) !== false) {
            $containsText = true;
            break; // Exit the loop as soon as a match is found
        }
    }

    if (!$containsText) {
        Router::any('(:any)/(:any)', 'Controllers\Site@news_inner');
    }
// NEWS INNER ROUTE END


Router::any('(:any)', 'Controllers\Channels@inner');

// Router::any('slug/all','Models\NewsModel@getSlugAll');



/** Module routes. */
//Hooks::addHook('meta', 'Controllers\Site@meta');
//$hooks = Hooks::get();
//$hooks->run('routes');


$module=Url::getModule();

/** If no route found. */
if($module==false) Router::error('Core\Error@index');
else Router::error('Core\Error@module_index');

/** Turn on old style routing. */
Router::$fallback = true;

/** Execute matched routes. */
Router::dispatch($module);