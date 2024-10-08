<?php
/**
 * Router - routing urls to closurs and controllers - modified from https://github.com/NoahBuscher/Macaw
 *
 */

namespace Core;

use Core\View;
use Helpers\Console;
use Helpers\Url;

/**
 * Router class will load requested controller / closure based on url.
 */
class Router
{
    /**
     * Fallback for auto dispatching feature.
     *
     * @var boolean $fallback
     */
    public static $fallback = true;

    /**
     * If true - do not process other routes when match is found
     *
     * @var boolean $halts
     */
    public static $halts = true;

    /**
     * Array of routes
     *
     * @var array $routes
     */
    public static $routes = [];

    /**
     * Array of methods
     *
     * @var array $methods
     */
    public static $methods = [];

    /**
     * Array of callbacks
     *
     * @var array $callbacks
     */
    public static $callbacks = [];

    /**
     * Set an error callback
     *
     * @var null $errorCallback
     */
    public static $errorCallback;

    /** Set route patterns */
    public static $patterns = [
        ':any' => '[^/]+',
        ':num' => '-?[0-9]+',
        ':all' => '.*',
        ':hex' => '[[:xdigit:]]+',
        ':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}',
    ];

    /**
     * Defines a route with or without callback and method.
     *
     * @param string $method
     * @param array @params
     */
    public static function __callStatic($method, $params)
    {
        // $uri = dirname($_SERVER['PHP_SELF']).'/'.$params[0];
        $uri = '/' . $params[0];
        $callback = $params[1];

        // Güncellenmiş desenler
        $patterns = [
            ':any' => '[^/]+',
            ':all' => '.*',
            ':hex' => '[[:xdigit:]]+',
            ':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}',
        ];

        // Eğer numara varsa, opsiyonel hale getir
        $uri = preg_replace('/\/(:num)?/', '/(:num)?', $uri);

        // Desenleri güncelle
        $patterns[':num'] = '-?[0-9]+';
        $patterns[':num'] = $patterns[':num'] . '?';

        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);

        // Desenleri güncelle
        self::$patterns = $patterns;
    }

    /**
     * Defines callback if route is not found.
     *
     * @param string $callback
     */
    public static function error($callback)
    {
        self::$errorCallback = $callback;
    }

    /**
     * Don't load any further routes on match.
     *
     * @param  boolean $flag
     */
    public static function haltOnMatch($flag = true)
    {
        self::$halts = $flag;
    }

    /**
     * Call object and instantiate.
     *
     * @param  object $callback
     * @param  array  $matched  array of matched parameters
     * @param  string $msg
     */
    public static function invokeObject($callback, $matched = null, $msg = null)
    {
        $last = explode('/', $callback);
        $last = end($last);

        $segments = explode('@', $last);

        $controller = $segments[0];
        $method = $segments[1];

        $controller = new $controller($msg);

        call_user_func_array([$controller, $method], $matched ? $matched : []);
    }

    /**
     * autoDispatch by Volter9.
     *
     * Ability to call controllers in their controller/model/param way.
     */
    public static function autoDispatch($module = false)
    {
        $controller = ucfirst(Url::getController());
        $method = Url::getMethod();
        $args = Url::getArgs();

        // Check for file
        if ($module == false && !file_exists("app/Controllers/$controller.php") or $module == true && !file_exists('app/Modules/' . $module . "/Controllers/$controller.php")) {
            return false;
        }

        if ($module == false) {
            $controller = "\Controllers\\$controller";
        } else {
            $controller = "\Modules\\$module\Controllers\\$controller";
        }

        $c = new $controller();
        if (method_exists($c, $method)) {
            call_user_func_array([$c, $method], $args);

            //found method so stop
            return true;
        }

        return false;
    }

    /**
     * Runs the callback for the given request.
     */
    public static function dispatch($module = false)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $method = $_SERVER['REQUEST_METHOD'];

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        self::$routes = str_replace('//', '/', self::$routes);

        $found_route = false;

        // parse query parameters

        $query = '';
        $q_arr = [];
        if (strpos($uri, '&') > 0) {
            $query = substr($uri, strpos($uri, '&') + 1);
            $uri = substr($uri, 0, strpos($uri, '&'));
            $q_arr = explode('&', $query);
            foreach ($q_arr as $q) {
                $qobj = explode('=', $q);
                $q_arr[] = [$qobj[0] => $qobj[1]];
                if (!isset($_GET[$qobj[0]])) {
                    $_GET[$qobj[0]] = $qobj[1];
                }
            }
        }

        // check if route is defined without regex
        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);

            // foreach route position
            foreach ($route_pos as $route) {
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
                    $found_route = true;

                    //if route is not an object
                    if (!is_object(self::$callbacks[$route])) {
                        //call object controller and method
                        self::invokeObject(self::$callbacks[$route]);
                        if (self::$halts) {
                            return;
                        }
                    } else {
                        //call closure
                        call_user_func(self::$callbacks[$route]);
                        if (self::$halts) {
                            return;
                        }
                    }
                }
            }
            // end foreach
        } else {
            $pos = 0;

            // foreach routes
            foreach (self::$routes as $route) {
                $route = str_replace('//', '/', $route);

                if (($uri == '/partner' or $uri == '/user') && $route == '/(:any)') {
                    continue;
                }

                if (strpos($route, ':') !== false) {
                    $route = str_replace(array_keys(self::$patterns), array_values(self::$patterns), $route);
                }
                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    // ... Diğer kodlar burada yer alır
                }
                $pos++;
            }
        }

        if (self::$fallback) {
            //call the auto dispatch method
            $found_route = self::autoDispatch($module);
        }

        // run the error callback if the route was not found
        if (!$found_route) {
            if (!self::$errorCallback) {
                self::$errorCallback = function () {
                    header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");

                    $data['title'] = '404';
                    $data['error'] = 'Oops! Page not found..';

                    View::render('Error/404', $data, 'error');
                };
            }
            if (!is_object(self::$errorCallback)) {
                //call object controller and method
                self::invokeObject(self::$errorCallback, null, 'No routes found.');
                if (self::$halts) {
                    return;
                }
            } else {
                call_user_func(self::$errorCallback);
                if (self::$halts) {
                    return;
                }
            }
        }
    }
}
