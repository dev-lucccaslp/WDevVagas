<?php 

namespace App\Entity;

use \App\Db\Database;

use \PDO;

class Vaga {

    //idenficador unico da vaga
    // @var integer

    public $id;

    //titutlo da vaga
    // @var string

    public $titulo;

    // Descrição da vaga (pode conter HTML)
    // @var string
    
    public $descricao;
    
    //define se a vaga está ativa (S/N)
    // @var string(s/n)
    
    public $ativo;

    //data de publicação da vaga
    // @var string

    public $data;

    // metodo responsavel por cadastrar uma nova vaga no banco
    // return boolean
    public function cadastrar() {
        //DEFINIR A DATA
        $this->data = date('Y-m-d H:i:s');
      
        //INSERIR A VAGA NO BANCO
        $obDatabase = new Database('vagas');
        // echo "<pre>"; print_r($obDatabase); echo "</pre>"; exit;     TESTE DE CONEXÃO E FALHA NA CONEXÃO
        $this->id = $obDatabase->insert([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);

        // echo "<pre>"; print_r($this); echo "</pre>"; exit; 
        //ATRIBUIR O ID DA VAGA NA INSTANCIA
      
        //RETORNAR SUCESSO
        return true;
    }

    //METODO RESPONSAVEL POR ATUALIZAR A VAGA NO BANCO
    //@return Boolean
    public function atualizar(){
        return (new Database('vagas'))->update('id = '.$this->id,[
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);
    }

    // METODO RESPONSAVEL POR EXCLUIR A VAGA DO BANCO
    //RETURN BOOLEAN
    public function excluir() {
        return (new Database('vagas'))->delete('id = '.$this->id);
    }



    /*METODO RESPONSAVEL POR OBTER AS VAGAS DO BANCO DE DADOS 
    * @param string $where
    * @param string $order
    * @param string $$limit
    * @return array
    */
    public static function getVagas($where= null, $order = null, $limit = null){
        return (new Database('vagas'))->select($where, $order,$limit) // select = sereve para selecionar colunas dentro do banco de dados
                                      ->fetchAll(PDO::FETCH_CLASS,self::class);//fetchAll() = todo retorno sera transformado em um array
    }

    //METODO RESPONSAVEL POR BUSCAR UMA VAGA COM BASE EM SEU ID
    //@param integer $id
    //@return Vaga
    public static function getVaga($id){
        return (new Database('vagas'))->select('id = '.$id)
                                      ->fetchObject(self::class);
    }
};