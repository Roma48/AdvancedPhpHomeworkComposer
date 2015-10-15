<?php

    class Router {
        public $routes;
        public function __construct($routes_config){
            $this->routes = $routes_config;
        }
        public function get_uri(){
            if(!empty($_SERVER['REQUEST_URI'])) {
                return trim($_SERVER['REQUEST_URI'], '/');
            }
            if(!empty($_SERVER['PATH_INFO'])) {
                return trim($_SERVER['PATH_INFO'], '/');
            }
            if(!empty($_SERVER['QUERY_STRING'])) {
                return trim($_SERVER['QUERY_STRING'], '/');
            }
        }
        public function run(){
            $uri = $this->get_uri();
            foreach($this->routes as $pattern => $route){
                if (preg_match("~$pattern~", $uri)){
                    $internalRoute = preg_replace("~$pattern~", $route, $uri);
                    $segments = explode('/', $internalRoute);
                    $controller = ucfirst(array_shift($segments)).'Controller';
                    $action = 'action'.ucfirst(array_shift($segments));
                    $parameters = $segments;
                    $controllerFile = 'app/src/'.$controller.'.php';
                    if(file_exists($controllerFile)){
                        include_once($controllerFile);
                        return new $controller;
                    } else {
                        return header("Location: /home");
                    }
                }
            }
            return header("Location: /home");
        }
    }
