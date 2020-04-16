<?php

use Core\Router;
use Helpers\Hooks;
use Helpers\Url;

/** Define routes. */

//API
Router::any('ajax/subscribe/(:num)', 'Controllers\Ajax@subscribe');
Router::any('ajax/un_subscribe/(:num)', 'Controllers\Ajax@un_subscribe');

Router::any('ajax/like/(:num)', 'Controllers\Ajax@like');
Router::any('ajax/remove_like/(:num)', 'Controllers\Ajax@remove_like');

Router::any('ajax/dislike/(:num)', 'Controllers\Ajax@dislike');
Router::any('ajax/remove_dislike/(:num)', 'Controllers\Ajax@remove_dislike');


//SEO
Router::any('seo/insert_keywords', 'Controllers\Seo@insert_keywords');
Router::any('sitemaps/update_list', 'Controllers\Sitemap@update');
Router::any('find/(:any)', 'Controllers\Seo@index');


Router::any('/', 'Controllers\Site@index');
Router::any('sign-in', 'Controllers\Site@sign_in');
Router::any('login', 'Controllers\Site@login');
Router::any('login/(:any)', 'Controllers\Site@login');
Router::any('register', 'Controllers\Site@register');
Router::any('register/(:any)', 'Controllers\Site@register');

Router::any('about', 'Controllers\Site@about');
Router::any('privacy-policy', 'Controllers\Site@privacy');
Router::any('refund-policy', 'Controllers\Site@refund');




Router::any('contacts', 'Controllers\Site@contacts');
Router::any('contact-us', 'Controllers\Site@contacts');
Router::any('media', 'Controllers\Site@media');
Router::any('media/(:num)/(:any)', 'Controllers\Site@media_inner');

Router::any('news', 'Controllers\Site@news');
Router::any('news/(:num)/(:any)', 'Controllers\Site@news_inner');

Router::any('cat/(:num)/(:any)', 'Controllers\Site@cat');
Router::any('tags/(:num)/(:any)', 'Controllers\Site@tag_cat');
Router::any('tags/(:any)', 'Controllers\Site@tags');

Router::any('blog', 'Controllers\Blog@index');
Router::any('blog/(:num)/(:any)', 'Controllers\Blog@inner');
Router::any('blog/search/(:any)', 'Controllers\Blog@search');

Router::any('forum', 'Controllers\Forum@index');
Router::any('forum/(:num)/(:any)', 'Controllers\Forum@inner');
Router::any('forum/cat/(:num)/(:any)', 'Controllers\Forum@cat');
Router::any('forum/ask', 'Controllers\Forum@ask');



Router::any('message/(:num)', 'Controllers\Messages@inner');

//Authorization
Router::any('auth/google', 'Controllers\Auth@google');
Router::any('auth/facebook', 'Controllers\Auth@facebook_login');
Router::any('auth/facebook/callback', 'Controllers\Auth@facebook_callback');



Router::any('user_panel/logout', 'Controllers\UserPanel@logout');
Router::any('user_panel/profile', 'Controllers\UserPanel@profile');

Router::any('set/region/(:any)', 'Controllers\Settings@region');

Router::any('(:any)', 'Controllers\Channels@inner');



/** Module routes. */
//Hooks::addHook('meta', 'Controllers\Site@meta');
$hooks = Hooks::get();
$hooks->run('routes');


$module=Url::getModule();

/** If no route found. */
if($module==false) Router::error('Core\Error@index');
else Router::error('Core\Error@module_index');

/** Turn on old style routing. */
Router::$fallback = true;

/** Execute matched routes. */
Router::dispatch($module);