<?php

include_once "HomeController.php";


class LogoutController
{
    public function __construct()
    {
        new HomeController();
        session_destroy();
        header("Location: /home");
        exit;
    }
}