<?php
/**
 * PROJETO: API
 * DESCRICAO: API RESTful - Application Programming Interface
 *            baseada na arquitetura REST- Representational State
 *            Transfer (Transferência Representacional de Estado).
 * Base da API: Protocolo HTTP usando especificações dos métodos aceitos pelo endpoint.
 * Formato da resposta: JSON.
 * Cliente: TCC PUC Minas
 * Solicitante: Augusto
 *
 * Descricao do arquivo:
 * cadastro do cliente cidadao
 *
 * Desenvolvimento:
 * Augusto Arruda 
 * Email: augusto.rr.arruda@gmail.com
 * Cel: (092) 991848979
 * Manaus, 04 de abril de 2021.
 * 
 */


/* Define namespace */
namespace App\DAO;
/* alias/import */
//use App\DAO\Database;


abstract class Database{
    /*Método construtor do banco de dados*/
    private function __construct(){}
     
    /*Evita que a classe seja clonada*/
    private function __clone(){}
     
    /*Método que destroi a conexão com banco de dados e remove da memória todas as variáveis setadas*/
    public function __destruct() {
        $this->disconnect();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }
     

    private static $dbtype   = "mysql";
    private static $host     = "server-mysql";
    //private static $port     = "3306";
    private static $port     = "3306";
    private static $user     = "root";
    private static $password = "admin";
    //private static $db       = "fpflorem";
    //private static $db       = "sapopemba_20_01_2021";
    private static $db       = "poc_cidadao";

    //private static $fpf_projetos  = "fpf_projetos";
//    private static $fpf_projetos  = "sap_pessoa";
    private static $cid_usuario = "cid_usuario";
    private static $cid_perfil = "cid_perfil";
    private static $cid_solicitacao = "cid_solicitacao";
    
    
    
    /*Metodos que trazem o conteudo da variavel desejada
    @return   $xxx = conteudo da variavel solicitada*/
    private function getDBType()  {return self::$dbtype;}
    private function getHost()    {return self::$host;}
    private function getPort()    {return self::$port;}
    private function getUser()    {return self::$user;}
    private function getPassword(){return self::$password;}
    private function getDB()      {return self::$db;}   

    /*Metodos publicos*/
//    public function fpfProjetos()  {return self::$fpf_projetos;}
    public function cidUsuario()  {return self::$cid_usuario;}
    public function cidPerfil()  {return self::$cid_perfil;}
    public function cidSolicitacao()  {return self::$cid_solicitacao;}
    

    public function connect(){
        try
        {
            $connPdo = new \PDO(Database::getDBType().':host='.Database::getHost().';port='.Database::getPort().';dbname='.Database::getDB(), Database::getUser(), Database::getPassword());

        }
        catch (PDOException $i)
        {
            //se houver exceção, exibe
            die("Erro: <code>" . $i->getMessage() . "</code>");
        }

        return ($connPdo);
    }

//    private function disconnect(){
//        $this->conexao = null;
//    }
//     
//    /*Método select que retorna um VO ou um array de objetos*/
//    public function selectDB($sql,$params=null,$class=null){
//        $query=$this->connect()->prepare($sql);
//        $query->execute($params);
//         
//        if(isset($class)){
//            $rs = $query->fetchAll(PDO::FETCH_CLASS,$class) or die(print_r($query->errorInfo(), true));
//        }else{
//            $rs = $query->fetchAll(PDO::FETCH_OBJ) or die(print_r($query->errorInfo(), true));
//        }
//        self::__destruct();
//        return $rs;
//    }
//     
//    /*Método insert que insere valores no banco de dados e retorna o último id inserido*/
//    public function insertDB($sql,$params=null){
//        $conexao=$this->connect();
//        $query=$conexao->prepare($sql);
//        $query->execute($params);
//        $rs = $conexao->lastInsertId() or die(print_r($query->errorInfo(), true));
//        self::__destruct();
//        return $rs;
//    }
//     
//    /*Método update que altera valores do banco de dados e retorna o número de linhas afetadas*/
//    public function updateDB($sql,$params=null){
//        $query=$this->connect()->prepare($sql);
//        $query->execute($params);
//        $rs = $query->rowCount() or die(print_r($query->errorInfo(), true));
//        self::__destruct();
//        return $rs;
//    }
//     
//    /*Método delete que excluí valores do banco de dados retorna o número de linhas afetadas*/
//    public function deleteDB($sql,$params=null){
//        $query=$this->connect()->prepare($sql);
//        $query->execute($params);
//        $rs = $query->rowCount() or die(print_r($query->errorInfo(), true));
//        self::__destruct();
//        return $rs;
//    }
    
    
    
    
}



