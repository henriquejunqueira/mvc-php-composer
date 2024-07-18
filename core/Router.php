<?php

namespace core;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Router
{


  private $controller = 'Site'; // * armazena o nome da classe principal
  private $method = 'home'; // * armazena o nome da página que o usuário quer acessar
  private $param = []; // * armazena o parâmetro da url

  public function __construct(){
    $router = $this->url();

    if(file_exists('app/controllers/' . ucfirst($router[0]) . '.php')):
      $this->controller = $router[0];
      unset($router[0]); // * limpa o router
    endif;

    $class = "\\app\\controllers\\" . ucfirst($this->controller);
    $object = new $class;

    if(isset($router[1]) and method_exists($class, $router[1])):
      $this->method = $router[1];
      unset($router[1]); // * limpa o router
    endif;

    $this->param = $router ? array_values($router): [];

    call_user_func_array([$object, $this->method], $this->param);
  }

  private function url(){
    $parse_url = explode("/", filter_input(INPUT_GET, 'router', FILTER_SANITIZE_URL));
    return $parse_url;
  }
}