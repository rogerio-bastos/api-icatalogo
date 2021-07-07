<?php

use App\Core\Controller;

class Produtos extends Controller{

    //Lista todos os produtos
    public function index(){

        $produtoModel = $this->model("Produto");

        $produtos = $produtoModel->listarTodos();

        $produtos = array_map(function ($p){
            $p->categoria = ["id" => $p->categoria_id, "descricao" => $p->categoria];
            unset($p->categoria_id);
            return $p;
        }, $produtos);

        echo json_encode($produtos, JSON_UNESCAPED_UNICODE);
    }

    public function find($id){
        
        $produtoModel = $this->model("Produto");

        $produtoModel = $produtoModel->findById($id);

        if($produtoModel){
            //transformando a categoria em um objeto dentro do $produtoModel
            $produtoModel->categoria = ["id" => $produtoModel->categoria_id, "descricao" => $produtoModel->categoria];

            unset($produtoModel->categoria_id);
            
            echo json_encode($produtoModel, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(404);
            
            $erro = ["erro" => "Produto não encontrado."];

            echo json_encode($erro, JSON_UNESCAPED_UNICODE);    
        }
    }

    public function delete($id){
        $produtoModel = $this->model("Produto");

        $produtoModel = $produtoModel->findById($id);

        if(!$produtoModel){
            http_response_code(404);
            echo json_encode(["erro" => "Produto não encontrado."]);
            exit;
        }

        $produtoModel->id = $id;

        if($produtoModel->delete()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao excluir produto."]);
        }
    }
}