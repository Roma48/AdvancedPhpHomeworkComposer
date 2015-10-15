<?php
require_once "vendor/autoload.php";
class TemplateLoader {

    public function getTemplate($name){
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('app/templates');
        $twig = new Twig_Environment($loader, []);
        return $twig->loadTemplate($name);
    }

}