<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 18:09
 */

namespace Kernel;

use FastRoute;

class Http
{

    private $dispatcher;

    public $isAjax = false;

    public $uri;

    public $method;

    public function __construct() {
        $this->dispatcher = FastRoute\simpleDispatcher(Route::register());

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $this->uri = $uri;
        $this->method = $httpMethod;

        $all_headers = getallheaders();
        if (isset($all_headers['X-Requested-With']) && $all_headers['X-Requested-With'] === 'XMLHttpRequest') {
            $this->isAjax = true;
        }
    }

    public function dispatch() {
        $routeInfo = $this->dispatcher->dispatch($this->method, $this->uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                exit($this->isAjax ? Response::api(-1, '404 Not Found!') : Response::view('pages.404'));
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars

                if (isset($handler['middleware'])) {
                    Middleware::dispatch($handler['middleware']);
                }

                if (isset($handler['controller'])) {
                    $controller = explode('@', $handler['controller']);
                    exit(call_user_func_array($controller, $vars));
                }
                break;
        }
    }
}