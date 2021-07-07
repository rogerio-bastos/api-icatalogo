<?php

namespace App\Core;

class Router{

     private $controller;

     private $method;

     private $controllerMethod;

     private $params = [];

     function __construct(){

         //configurando cors e contéudo do response
         header('Access-Control-Allow-Origin: *');
         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
         header("Access-Control-Allow-Headers: Content-Type");
         header("content-type: application/json");

         //pega a url que está sendo acessada
         $url= $this->parseURL();

         //existe um controller com este nome
        if(file_exists("../App/Controllers/" . $url[1] . ".php")){
            
            $this->controller = $url[1];
        
            unset($url[1]);
        
        }else if(empty($url[1])){

            $this->controller = "produtos";
        
        }else{
            $this->controller = "erro404";
        }

        require_once "../App/Controllers/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        //pegando o http method da requisição
        $this->method = $_SERVER["REQUEST_METHOD"];

        //pegando o método do controller baseado no http method
        switch($this->method){
            
            case "GET":
                if(isset($url[2])){
                    $this->controllerMethod = "find";
                    $this->params = [$url[2]];
                }else{
                    $this->controllerMethod = "index";
                }
                
                break;
                
            case "POST":
                $this->controllerMethod = "store";
                break;
            
            case "PUT":
                $this->controllerMethod = "update";
                if(isset($url[2]) && is_numeric($url[2])){
                    $this->params = [$url[2]];
                }else{
                    http_response_code(400);
                    echo json_encode(["erro" => "É necessário informar um id"]);
                    exit;
                }
                break;
            
            case "DELETE": 
                $this->controllerMethod = "delete";
                if(isset($url[2]) && is_numeric($url[2])){
                    $this->params = [$url[2]];
                }else{
                    http_response_code(400);
                    echo json_encode(["erro" => "É necessário informar um id"]);
                    exit;
                }
                break;
            
            default: 
                echo "Método não suportado";
                exit;
                break;
        }

        //chamando o método e o controller 
        call_user_func_array([$this->controller, $this->controllerMethod], $this->params);

     }

     //retorna o controllers, method e os params da url em um vetor
     private function parseURL(){ 
        return explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);

        
     }
}