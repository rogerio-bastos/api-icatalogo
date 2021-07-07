<?php

use App\Core\Model;

class Categoria{
    public $id;
    public $descricao;

    public function listarTodas(){

        $sqlRead = " SELECT * FROM tbl_categoria ";

        $stmt = MODEL::getConnection()->prepare($sqlRead);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchALL(\PDO::FETCH_OBJ);

            return $result;
            echo($result);
        } else {
            return [];
        }
    }

    public function insert(){

        //declaramos o sql, com um ponto de bind (?)
        $sqlInsert = " INSERT INTO tbl_categoria(descricao) VALUES (?) ";

        //preparamos a instância para inserir
        $stmt = Model::getConnection()->prepare($sqlInsert);

        //substitui o primeiro ? pela variável descrição
        $stmt->bindValue(1, $this->descricao);


        //executa
        if($stmt->execute()){
            //se der certo, atribui o id inserido na classe
            $this->id = Model::getConnection()->lastInsertId();
            //retorna a própria classe
            return $this;
        }else{
            //se der errado, retorna falso
            return false;
        }

    }

    public function findById($id){
        $sqlSelect = " SELECT * FROM tbl_categoria WHERE id = ? ";
        $stmt = Model::getConnection()->prepare($sqlSelect);
        $stmt->bindValue(1, $id);
        
        if($stmt->execute()){
            $categoria = $stmt->fetch(PDO::FETCH_OBJ);

            if(!$categoria){
                return false;
            }

            $this->id = $categoria->id;
            $this->descricao = $categoria->descricao;

            return $this;
        }else{
            return false;
        }

    }

    public function update(){
        $sqlUpdate = " UPDATE tbl_categoria SET descricao = ? WHERE id = ? ";

        $stmt = Model::getConnection()->prepare($sqlUpdate);
        
        $stmt->bindValue(1, $this->descricao);
        
        $stmt->bindValue(2, $this->id);

        return $stmt->execute();
    }

    public function delete(){
        $sqlDelete = " DELETE FROM tbl_categoria WHERE id = ? ";

        $stmt = Model::getConnection()->prepare($sqlDelete);
        
        $stmt->bindValue(1, $this->id);

        return $stmt->execute();

    }

    public function getProducts(){
        $sqlGet = " SELECT p.* FROM tbl_categoria c INNER JOIN tbl_produto p ON p.categoria_id = c.id WHERE c.id = ? ";

        $stmt = Model::getConnection()->prepare($sqlGet);
        
        $stmt->bindValue(1, $this->id);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $resultados;
        }else{
            return [];
        }
    }
}
