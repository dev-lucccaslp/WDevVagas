<?php 

namespace App\Db;

use \PDO;
use PDOException;

class Database{

    // HOST DE CONEXÃO COM BANCO DE DADOS
    const HOST = 'localhost';

    // NOME DO BANCO DE DADOS
    const NAME = 'wdev_vagas';

    // USUARIO DO BANCO
    const USER = 'root';
    
    //SENHA DO BANCO
    const PASS = 'teste123';

    //TABELA A SER MANIPULADA
    private $table;

    //INSTANCIA DE CONEXÃO COM BANCO DE DADOS (PDO)
    private $connection;

    // DEFINE TABELA E INSTANCIA DE CONEXÃO
    public function __construct($table = null) {
        $this-> table = $table;
        $this-> setConection();
    }

    //FUNÇÃO RESPONSAVE POR CRIAR UMA CONEXÃO COM O BANCO DE DADOS
    private function setConection(){
        try{
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME, self::USER, self::PASS); // define a conexão
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // caso ocorra algum erro ele apresentara um erro faltal
        } catch(PDOException $e){
            die('ERROR: '.$e->getMessage()); // caso nao conecte no banco ele irá apresentar este erro
        }
    }

    //METODOS RESPONSAVEIS POR EXECUTAR QUERIES DENTRO DB
    public function execute ($query, $params = []){
        try{
            $statement = $this->connection->prepare($query);
            $statement-> execute($params);
            return $statement;
        } catch(PDOException $e) {
            die('ERROR: '.$e->getMessage()); // caso nao conecte no banco ele irá apresentar este erro
        }
    }



    // METODO RESPONSAVEL POR INSERIR DADOS NO BANCO
    // TYPE [FIELD => VALUE]
    //RETUNR [ INTEGER]
    public function insert($values){
        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        // $query = 'INSERT INTO vagas (titulo,descricao,ativo,data) VALUES ("teste", "teste2", "s", "2022-11-04 00:00:00)'; // QUERY EM UM FORMATO PADRÃO SEM PDO
        $query = 'INSERT INTO '.$this->table.' ('.implode(',' ,$fields).') VALUES ('.implode(',' ,$binds).')'; // SEM RECEBER PARAMETROS O PDO FARA UMA VERIFICAÇÃO MELHOR DENTRO DO DB
        
        //EXECUTA O INSERT
        $this->execute($query, array_values($values));

        //RETORNA O ID INSERIDO
        return $this->connection->lastInsertId();
    }
    /*METODO RESPONSAVEL POR EXECUTAR UMA CONSULTA NO BANCO
    * @param string $where
    * @param string $order
    * @param string $limit
    * @param string $fields
    * @return PDOstatement
    */
    public function select($where= null, $order = null, $limit = null, $fields= '*'){
        //DADOS DA QUERY
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        //MONTAR A QUERY
        $query = 'SELECT '.$fields. 'FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

        //EXECUTA A QUERY
        return $this->execute($query);
    }
    //METODO RESPONSAVEL POR EXECUTAR ATUALIZAÇÕES NO BANCO DE DADOS
    //@param string $where
    //@param array $values [ field => value]
    //@return boolean
    public function update($where,$values){
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

        //EXECUTAR A QUERY
        $this->execute($query,array_values($values));

        //RETORNA SUCESSO
        return true;
    }

    //METODO RESPONSAVEL POR EXCLUIR DADOS DO BANCO
    //$WHERE = STRING
    //RETURN BOOLEAN
    public function delete($where){
        //MONTA A QUERY
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

        //EXECUTA A QUERY
        $this->execute($query);

        //RETORNA SUCESSO
        return true;
    }
};