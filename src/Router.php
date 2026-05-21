<?php
    namespace Test;

    class Router
    {
        protected $routes = [];

        private function addRoute($route, $controller, $action, $method, $permissions = [])
        {
            $this->routes[$method][$route] = [
                'controller' => $controller, 
                'action' => $action,
                'permissions' => $permissions
            ];
        }

        public function get($route, $controller, $action, $permissions = [])
        {
            $this->addRoute($route, $controller, $action, "GET", $permissions);
        }

        public function post($route, $controller, $action, $permissions = [])
        {
            $this->addRoute($route, $controller, $action, "POST", $permissions);
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

               $route = $this->routes[$method][$uri];

                // Protected route?
                if (!empty($route['permissions'])) {

                    // Not logged in
                    if (!isLoggedIn()) {
                        header('Location: /login');
                        exit;
                    }

                    // Default
                    $isAuthorized = false;

                    // Permission check
                    foreach ($route['permissions'] as $permission) {
                        if (hasPermission($permission)) {
                            $isAuthorized = true;
                            break;
                        }
                    }

                    // No permission abort
                    if (!$isAuthorized) {
                        abort(403);
                    }
                }

                $controller = $this->routes[$method][$uri]['controller'];
                $action = $this->routes[$method][$uri]['action'];

                $controller = new $controller();
                $controller->$action();
                
            } else {
                http_response_code(404);
                abort(404);
            } 
        }
    }

?>