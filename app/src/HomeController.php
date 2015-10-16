<?php

require_once "TemplateLoader.php";
require_once "vendor/autoload.php";

class HomeController
{

    public function __construct()
    {
        $this->login();
    }

    public function login()
    {
        session_start();
        if (isset($_POST['login']) && $_POST['password']) {

            $client = new GuzzleHttp\Client();
            $result = $client->post('http://geekhub.docebosaas.com/api/user/authenticate', ['form_params' => [
                'username' => $_POST['login'],
                'password' => $_POST['password']
            ]]);

            $this->getUser($result->getStatusCode());

        } else {
            $template = new TemplateLoader();
            $render = $template->getTemplate('login.html');
            if(isset($_SESSION['docebo_name'])){
                $today = \Carbon\Carbon::now();
                echo $render->render([
                    'login' => true,
                    'firstname' => $_SESSION['docebo_name'],
                    'lastname' => $_SESSION['docebo_last'],
                    'email' => $_SESSION['docebo_email'],
                    'today' => $today
                ]);
            } else {
                echo $render->render(['login' => false]);
            }
        }
    }

    public function getUser($status){
        if ($status === 200) {
            $get_user = new GuzzleHttp\Client();
            $secret = 'WEFEpcGFWIq!ya7au-t-Krt_JnJKD6_o9K*l';
            $key = 'L*D3iK5bMWk9hYRirt_h!pP2';
            $codice_sha1 = sha1(implode(',', ['userid' => trim($_POST['login']), 'also_check_as_email' => true]) . ',' . $secret);
            $codice = base64_encode($key . ':' . $codice_sha1);
            $user = $get_user->post('http://geekhub.docebosaas.com/api/user/checkUsername', [
                'form_params' => ['userid' => $_POST['login'], 'also_check_as_email' => true],
                'headers' => [
                    'X-Authorization' => $codice
                ]
            ]);
            $json = json_decode($user->getBody()->getContents());
            $_SESSION['docebo_name'] = $json->firstname;
            $_SESSION['docebo_last'] = $json->lastname;
            $_SESSION['docebo_email'] = $json->email;
            $template = new TemplateLoader();
            $render = $template->getTemplate('login.html');
            $today = \Carbon\Carbon::now();
            echo $render->render([
                'login' => true,
                'firstname' => $_SESSION['docebo_name'],
                'lastname' => $_SESSION['docebo_last'],
                'email' => $_SESSION['docebo_email'],
                'today' => $today
            ]);
        } else {
            header("Location: /home");
        }
    }

}
