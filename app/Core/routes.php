<?php

use Core\Router;
use Helpers\Hooks;
use Helpers\Url;

/** Define routes. */

//API
Router::any('api/sms/receive/8Aj2M1astF4W/(:any)', 'Controllers\Sms@receive');


//SEO
Router::any('seo/insert_keywords', 'Controllers\Seo@insert_keywords');
Router::any('sitemaps/update_list', 'Controllers\Sitemap@update');
Router::any('find/(:any)', 'Controllers\Seo@index');

//APP Links
Router::any('locations1', 'Controllers\Site@locations_app');
Router::any('contact-us1', 'Controllers\Site@contacts_app');
Router::any('about-us1', 'Controllers\Site@about_app');

//Iframe Links
Router::any('location-iframe', 'Controllers\Site@locations_iframe');

Router::any('/', 'Controllers\Site@index');
Router::any('sign-in', 'Controllers\Site@sign_in');
Router::any('login', 'Controllers\Site@login');
Router::any('login/(:any)', 'Controllers\Site@login');
Router::any('register', 'Controllers\Site@register');
Router::any('register/(:any)', 'Controllers\Site@register');

Router::any('about', 'Controllers\Site@about');
Router::any('privacy-policy', 'Controllers\Site@privacy');
Router::any('refund-policy', 'Controllers\Site@refund');
Router::any('what-we-do', 'Controllers\Site@what_we_do');
Router::any('airport-service', 'Controllers\Site@airport_service');
Router::any('faqs', 'Controllers\Site@faqs');
Router::any('testimonials', 'Controllers\Testimonials@index');
Router::any('who-we-are', 'Controllers\Site@about');
Router::any('how-it-works', 'Controllers\Site@how_it_works');
Router::any('schedule-an-appointment', 'Controllers\Appointments@index');



Router::any('locations', 'Controllers\Site@locations');
Router::any('apartments', 'Controllers\Apartments@index');
Router::any('apartments/(:num)/(:any)', 'Controllers\Apartments@inner');
Router::any('apartments/search/(:any)', 'Controllers\Apartments@search');
Router::any('apartments/downtown-la', 'Controllers\Apartments@downtown_la');
Router::any('apartments/koreatown', 'Controllers\Apartments@koreatown');
Router::any('apartments/santa-monica', 'Controllers\Apartments@santa_monica');

Router::any('contacts', 'Controllers\Site@contacts');
Router::any('contact-us', 'Controllers\Site@contacts');
Router::any('media', 'Controllers\Site@media');
Router::any('media/(:num)/(:any)', 'Controllers\Site@media_inner');

Router::any('news', 'Controllers\Site@news');
Router::any('news/(:num)/(:any)', 'Controllers\Site@news_inner');

Router::any('blog', 'Controllers\Blog@index');
Router::any('blog/(:num)/(:any)', 'Controllers\Blog@inner');
Router::any('blog/search/(:any)', 'Controllers\Blog@search');

Router::any('forum', 'Controllers\Forum@index');
Router::any('forum/(:num)/(:any)', 'Controllers\Forum@inner');
Router::any('forum/cat/(:num)/(:any)', 'Controllers\Forum@cat');
Router::any('forum/ask', 'Controllers\Forum@ask');


Router::any('roommate/(:num)/(:any)', 'Controllers\Roommates@inner');

Router::any('message/(:num)', 'Controllers\Messages@inner');

//Authorization
Router::any('auth/google', 'Controllers\Auth@google');
Router::any('auth/facebook', 'Controllers\Auth@facebook_login');
Router::any('auth/facebook/callback', 'Controllers\Auth@facebook_callback');



Router::any('user_panel/logout', 'Controllers\UserPanel@logout');
//Router::any('userpanel/dashboard', 'Controllers\UserPanel@dashboard');
Router::any('user_panel/profile', 'Controllers\UserPanel@profile');
//Router::any('user/address', 'Controllers\UserPanel@address');
//Router::any('user/orders', 'Controllers\UserPanel@orders');
//Router::any('user/checkout', 'Controllers\UserPanel@checkout');



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