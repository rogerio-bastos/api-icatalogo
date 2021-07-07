<?php
session_start();
use App\Core\Controller;

class Categorias extends Controller{

    public function index(){

        $categoriaModel = $this->model("Categoria");

        $dados = $categoriaModel->listarTodas();

        //colocar os dados no corpo da requisição
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    }

    public function find($id){

        $categoriaModel = $this->model("Categoria");

        $categoriaModel = $categoriaModel->findById($id);

        if($categoriaModel){

            echo json_encode($categoriaModel, JSON_UNESCAPED_UNICODE);

        }else{
            //não encontrou a categoria pelo id

            //Alterando o status code da request -  importante informar o status code correto
            http_response_code(404);

            $erro = ["erro" => "Categoria não encontrada"];

            echo json_encode($erro, JSON_UNESCAPED_UNICODE);
        }

    }

    public function store(){

        //pegando o corpo da requisição
        $json = file_get_contents("php://input");

        //transformando o json (string) em objeto php
        $novaCategoria = json_decode($json);

        //instanciamos o model, colocando nele a descrição recebeida
        $categoriaModel = $this->model("Categoria");
        $categoriaModel->descricao = $novaCategoria->descricao;

        //chamando o método inserir, que salva no banco de dados
        $categoriaModel = $categoriaModel->insert();


        //verificamos se deu certo, e enviando a resposta apropriada
        if($categoriaModel){
            http_response_code(201);
            echo json_encode($categoriaModel);

        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir categoria"]);

        }
    }

    public function update($id){
        $json = file_get_contents("php://input");

        $categoriaParaAtualizar = json_decode($json);

        $categoriaModel = $this->model("Categoria");

        $categoriaModel = $categoriaModel->findById($id);

        //verificando se o id passado não existe, retornado erro
        if(!$categoriaModel){
            http_response_code(404);
            echo json_encode(["erro" => "Categoria não encontrada."]);
            exit;
        }

        $categoriaModel->descricao = $categoriaParaAtualizar->descricao;
        $categoriaModel->id = $id;
    
        if($categoriaModel->update()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao atualizar a categoria."]);
        }
    }

    public function delete($id){
        $categoriaModel = $this->model("Categoria");

        $categoriaModel = $categoriaModel->findById($id);

        //verificando se o id passado não existe, retornado erro
        if(!$categoriaModel){
            http_response_code(404);
            echo json_encode(["erro" => "Categoria não encontrada."]);
            exit;
        }

        $categoriaModel->id = $id;

        $produtos= $categoriaModel->getProducts();

        if($produtos != []){
            http_response_code(404);
            echo json_encode(["erro" => "Exclua os produtos que pertence a essa categoria, não pôde ser excluída."]);
            exit;
        }

        if($categoriaModel->delete()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao excluir a categoria."]);
        }
    }


}