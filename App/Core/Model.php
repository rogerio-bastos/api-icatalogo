<?php
//o namespace deve sempre corresponder a estrutura de pasta que a classe pertence
namespace App\Core;

class Model{
    
    //Utlizando o padrão de Projeto singleton
    private static $conexao;

    public static function getConnection(){
        
        //Criar uma conexão, se caso não existir
        if(!isset(self::$conexao)){
            self::$conexao = new \PDO("mysql:host=localhost;port=3306;dbname=icatalogo;", "root", "Work@bench");
        }

        //retornamos a conexão
        return self::$conexao;
    }
}