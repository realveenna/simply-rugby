<?php
    namespace Test;

    class Router
    {
        protected $routes = [];

        private function addRoute($route, $controller, $action, $method)
        {
            $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
        }

        public function get($route, $controller, $action)
        {
            $this->addRoute($route, $controller, $action, "GET");
        }

        public function post($route, $controller, $action)
        {
            $this->addRoute($route, $controller, $action, "POST");
        }

        public function dispatch()
        {
            $uri = strtok($_SERVER['REQUEST_URI'], '?');
            $method =  $_SERVER['REQUEST_METHOD'];

            // Base Path
            $base = '/simply-rugby/Test/public';
            $uri = str_replace($base, '', $uri);

            // Handle Empty Root
            if ($uri === '' || $uri === false) {
                $uri = '/';
            }
            

            if (array_key_exists($uri, $this->routes[$method])) {
                $controller = $this->routes[$method][$uri]['controller'];
                $action = $this->routes[$method][$uri]['action'];

                $controller = new $controller();
                $controller->$action();
                
            } else {
                http_response_code(404);
                $controller = new \Test\Controllers\Error();
                $controller->notFound();
                exit;
            } 

            
        }
    }

?>