<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 18:09
 */

namespace Kernel;

use Common\UserHelper;
use FastRoute;

class Http
{

    private $dispatcher;

    public $isAjax = false;

    public $uri;

    public $method;

    public function __construct() {
        $this->dispatcher = FastRoute\simpleDispatcher(Route::register());

        $this->uri = Request::uri();
        $this->method = Request::method();
        $this->isAjax = Request::isAjax();

        UserHelper::initialize();
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