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
namespace App\Models;
/* alias/import */
use App\DAO\Database;//banco de dados
use RdKafka;//classe Apache Kakfa

class Solicitacao
{
    private static $fail_solicitacaoinexistente = "Solicitação não existe!";
    private static $fail_criarsolicitacao = "Falha ao realizar o cadastro da solicitação!";
    private static $success_criarsolicitacao = "Cadastro de solicitação realizada com sucesso!";
    
    //LISTENER_INSIDE trafego interno na rede Docker
    //LISTENER_DOCKER trafego da maquina Docker-host (localhost)
    //LISTENER_OUTSIDE trafego externo, alcancando o host Docker no ip ${IP_SERVER}
    //private static $men_topic_brokerList = "{kafka1:19091, kafka2:29091, kafka3:39091}"; //LISTENER_INSIDE
    //private static $men_topic_brokerList = "{localhost:19092, localhost:29092, localhost:39092}"; //LISTENER_DOCKER
    private static $men_topic_brokerList = "{192.168.0.18:19093, 192.168.0.18:29093, 192.168.0.18:39093}"; //LISTENER_OUTSIDE
    private static $men_topic_header = ['Sistema' => 'SGM-Sistema de Gestao Municipal', 'API' => 'Mensageria', 'Descricao' => 'TCC Puc Minas', 'Desenvolvimento' => 'Augusto Arruda'];
    private static $men_topic_name = "solicitacaocreate";
    private static $men_topic_client = "rdkafka";
    private static $men_topic_tipo = "solicitacao_criada";
    
    

    public static function select($id)
    {
        //instancia a conexao e a tabela
        $connPdo = Database::connect();
        $connTable = Database::cidSolicitacao();
        //consulta
        $sql = 'SELECT * FROM '.$connTable.' WHERE id = :id';
        $stmt = $connPdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception(self::$fail_solicitacaoinexistente);
        }
    }

    
    
 
    
    
    /**
     * retorna solicitacao a partir do identificador
     */
    public static function selectSolicitante($solicitanteid)
    {
        //instancia a conexao e a tabela
        $connPdo = Database::connect();
        $connTable_cidSolicitacao =  Database::cidSolicitacao();
        $sql = "SELECT
                id,
                processo,
                tiposervico,
                descricao,
                data_solicitacao DATA_SOLICTACAO_EN,
                DATE_FORMAT(data_solicitacao, '%d/%m/%Y %H:%i') DATA_SOLICTACAO_PTBR,
                solicitante,
                data_finalizacao,
                responsavel,
                status,
                (CASE WHEN status = '0' THEN 'Instanciado' WHEN status = '1' THEN 'Em andamento' WHEN status = '2' THEN 'Finalizado' ELSE 'Sem Definição' END) STATUS_NOME
                FROM `$connTable_cidSolicitacao` WHERE solicitante LIKE '[{%id%:%$solicitanteid%' ";
        $stmt = $connPdo->query($sql);
        $row = 0;//numero de linhas - inicio
        $retornoconsulta = array();//instancia o array
        //gera array com indice
        while ($dadoconsulta = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $retornoconsulta[$row]['id'] = $dadoconsulta['id'];
            $retornoconsulta[$row]['processo'] = $dadoconsulta['processo'];
            $retornoconsulta[$row]['tiposervico'] = $dadoconsulta['tiposervico'];
            $retornoconsulta[$row]['descricao'] = $dadoconsulta['descricao'];
            $retornoconsulta[$row]['data_solicitacao'] = $dadoconsulta['DATA_SOLICTACAO_PTBR'];
            $retornoconsulta[$row]['solicitante'] = $dadoconsulta['solicitante'];
            $retornoconsulta[$row]['data_finalizacao'] = $dadoconsulta['data_finalizacao'];
            $retornoconsulta[$row]['responsavel'] = $dadoconsulta['responsavel'];
            $retornoconsulta[$row]['status'] = $dadoconsulta['STATUS_NOME'];
            $row++;
        }
        
        //Retorno JSON COM INDICE
        if ($stmt->rowCount() > 0) {
            return $retornoconsulta;
        }else{
            //throw new \Exception(self::$fail_solicitacaoinexistente);
            return NULL;
        }
        
    }

    
    
    
    
    
    
    
    
    
 
    
    public static function selectSolicitante_B($solicitanteid)
    {
        //instancia a conexao e a tabela
        $connPdo =  Database::connect();
        $connTable_cidSolicitacao =  Database::cidSolicitacao();
        //consulta
        $sql = 'SELECT * FROM '.$connTable_cidSolicitacao.' WHERE solicitante LIKE "%'.$solicitanteid.'%"';
        $stmt = $connPdo->prepare($sql);
        //$stmt->bindValue(':Solicitante', "%'.$solicitanteid.'%");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception(self::$fail_solicitacaoinexistente);
        }
    }
    
    
       
    
    
    
    public static function selectAll_B() 
    {
        //instancia a conexao e a tabela
        $connPdo =  Database::connect();
        $connTable_cidSolicitacao =  Database::cidSolicitacao();
        //consulta
        $sql = 'SELECT * FROM '.$connTable_cidSolicitacao;
        $stmt = $connPdo->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception(self::$fail_solicitacaoinexistente);
        }
    }
    
    
    
    
    
    /**
     * retorna solicitacao a partir do identificador
     */
    public static function selectAll()
    {
        //instancia a conexao e a tabela
        $connPdo = Database::connect();
        $connTable_cidSolicitacao =  Database::cidSolicitacao();
        $sql = "SELECT
                id,
                processo,
                tiposervico,
                descricao,
                data_solicitacao DATA_SOLICTACAO_EN,
                DATE_FORMAT(data_solicitacao, '%d/%m/%Y %H:%i') DATA_SOLICTACAO_PTBR,
                solicitante,
                data_finalizacao,
                responsavel,
                status,
                (CASE WHEN status = '0' THEN 'Instanciado' WHEN status = '1' THEN 'Em andamento' WHEN status = '2' THEN 'Finalizado' ELSE 'Sem Definição' END) STATUS_NOME
                FROM `$connTable_cidSolicitacao` ";
        $stmt = $connPdo->query($sql);
        $row = 0;//numero de linhas - inicio
        $retornoconsulta = array();//instancia o array
        //gera array com indice
        while ($dadoconsulta = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $retornoconsulta[$row]['id'] = $dadoconsulta['id'];
            $retornoconsulta[$row]['processo'] = $dadoconsulta['processo'];
            $retornoconsulta[$row]['tiposervico'] = $dadoconsulta['tiposervico'];
            $retornoconsulta[$row]['descricao'] = $dadoconsulta['descricao'];
            $retornoconsulta[$row]['data_solicitacao'] = $dadoconsulta['DATA_SOLICTACAO_PTBR'];
            $retornoconsulta[$row]['solicitante'] = $dadoconsulta['solicitante'];
            $retornoconsulta[$row]['data_finalizacao'] = $dadoconsulta['data_finalizacao'];
            $retornoconsulta[$row]['responsavel'] = $dadoconsulta['responsavel'];
            $retornoconsulta[$row]['status'] = $dadoconsulta['STATUS_NOME'];
            $row++;
        }
        
        //Retorno JSON COM INDICE
        if ($stmt->rowCount() > 0) {
            return $retornoconsulta;
        }else{
            throw new \Exception(self::$fail_solicitacaoinexistente);
        }
        
    }

    
    
    
    
    
    
       
 
    /**
     * criar nova solicitacao
     */
    public static function create($data)
    {
        $status = 0;
        $connPdo = Database::connect();
        $connTable_cidSolicitacao = Database::cidSolicitacao();
        $sql = 'INSERT INTO '.$connTable_cidSolicitacao.' (id, processo, tiposervico, descricao, data_solicitacao, solicitante, data_finalizacao, responsavel, status) VALUES
                (:Id, :Processo, :Tiposervico, :Descricao, sysdate(), :Solicitante, :Data_finalizacao, :Responsavel, :Status)';
        $stmt = $connPdo->prepare($sql);
        $stmt->bindValue(':Id', NULL);
        $stmt->bindValue(':Processo', $data['novasolicitacao_processo']);
        $stmt->bindValue(':Tiposervico', $data['novasolicitacao_tiposervico']);
        $stmt->bindValue(':Descricao', $data['novasolicitacao_descricao']);
        //$stmt->bindValue(':Data_solicitacao', $data['nome']);
        $stmt->bindValue(':Solicitante', $data['novasolicitacao_solicitante']);
        $stmt->bindValue(':Data_finalizacao', NULL);
        $stmt->bindValue(':Responsavel', NULL);
        $stmt->bindValue(':Status', $status);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            //return self::$success_criarsolicitacao;
            //Gerar mensageria
            return Solicitacao::mensagemsolicitacaocreate($data['novasolicitacao_processo'], $data['novasolicitacao_tiposervico'], $data['novasolicitacao_solicitante']);
        } else {
            throw new \Exception(self::$fail_criarsolicitacao);
        }
        
    }


    
 
    /*
     * MENSAGERIA KAFKA
     * PRODUTOR
     * Gera mensagem para o recurso de mensageria
     */
    public static function mensagemsolicitacaocreate($processo, $tiposervico, $solicitante)
    {
        /*
         * Buscar dados da solicitacao criada para anexar dados na mensageria
         */
        $connPdo = Database::connect();
        $connTable_cidSolicitacao = Database::cidSolicitacao();
        $sql = 'SELECT
                id,
                processo,
                tiposervico,
                descricao,
                data_solicitacao,
                solicitante,
                data_finalizacao,
                responsavel,
                status
                FROM '.$connTable_cidSolicitacao.'
                WHERE processo = :Processo AND tiposervico = :Tiposervico AND solicitante = :Solicitante';
        $stmt = $connPdo->prepare($sql);
        $stmt->bindValue(':Processo', $processo);
        $stmt->bindValue(':Tiposervico', $tiposervico);
        $stmt->bindValue(':Solicitante', $solicitante);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $retorno = $stmt->fetch(\PDO::FETCH_ASSOC);
            $sol_id = $retorno['id'];
            $sol_processo = $retorno['processo'];
            $sol_tiposervico = $retorno['tiposervico'];
            $sol_descricao = $retorno['descricao'];
            $sol_data_solicitacao = $retorno['data_solicitacao'];
            $sol_solicitante = $retorno['solicitante'];
            $sol_data_finalizacao = $retorno['data_finalizacao'];
            $sol_responsavel = $retorno['responsavel'];
            $sol_status = $retorno['status'];
        } else {
            throw new \Exception(self::$fail_solicitacaoinexistente);
        }

        
        /*
         * Executar mensageria
         */
        $topicBrokerList = self::$men_topic_brokerList;
        /*Definicao das variaveis*/
        $topicName = self::$men_topic_name;
        $topicClient = self::$men_topic_client;//default
        $topicId = $sol_id;
        $topicTimeoutMs = 10000;
        $topicHeader = self::$men_topic_header;
        $topicMessage = [
            'tipo' => self::$men_topic_tipo,
            'id' => $sol_id,
            'processo' => $sol_processo,
            'tiposervico' => $sol_tiposervico,
            'descricao' => $sol_descricao,
            'data_solicitacao' => $sol_data_solicitacao,
            'solicitante' => $sol_solicitante,
            'data_finalizacao' => $sol_data_finalizacao,
            'responsavel' => $sol_responsavel,
            'status' => $sol_status
        ];
        $topicMessageJson = json_encode($topicMessage);

        /*Instancia as configuracoes do rdkafka*/
        $conf = new RdKafka\Conf();
        $conf->set('client.id', $topicClient);
        //$conf->set('group.id', $topicGroupid);
        //$conf->set('metadata.broker.list', $topicBrokerList);
        //$conf->set('enable.idempotence', 'true');
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');

        /*Instancia o producer do rdkafka*/
        $producer = new RdKafka\Producer($conf);
        $producer->addBrokers($topicBrokerList);//definicao do(s) Brocker(s)
        $topic = $producer->newTopic($topicName);//cricao do tópico
        //$topic->produce(RD_KAFKA_PARTITION_UA, 0, $topicMessageJson, $topicId);//retorno sem header
        $topic->producev(RD_KAFKA_PARTITION_UA, 0, $topicMessageJson, $topicId, $topicHeader);//retorno com header
        $producer->poll(0);
        //$poll = $producer->poll(0);
        //Ignorar as mensagens que ainda não foram totalmente enviadas 
        //$producer->purge(RD_KAFKA_PURGE_F_QUEUE);
        //Destroi a instância do produtor para garantir que todas as solicitações de produção 
        //enfileiradas e em andamento sejam concluídas sob pena de perda de mensagem
        $result = $producer->flush($topicTimeoutMs);//destroi a instancia do producer

        /*Retorna Instancia o producer do rdkafka*/
        if( $result === 0){
            //echo 'Produção da mensagem com o tópico '.$topicName.' para os brokers '.$topicBrokerList.'!';
            return self::$success_criarsolicitacao;
        }else{
            //echo 'Erro na Produção da mensagem!';
            throw new \Exception(self::$fail_criarsolicitacao);
        }
        
    }


        
    
    public static function insert($data)
    {
        
//        //instancia a conexao e a tabela
//        $connPdo =  Database::connect();
//        $connTable =  Database::preUsuario();
//        //consulta
//        $sql = 'INSERT INTO '.$connTable.' (id, email, senha, cria_data, cria_ip, cria_dispositivo, status)VALUES (:id, :ema, :sen, :crd, :crp, :cri, :cri)';
//        $stmt = $connPdo->prepare($sql);
//        $stmt->bindValue(':id', $data['id']);
//        $stmt->bindValue(':ema', $data['email']);
//        $stmt->bindValue(':sen', $data['senha']);
//        $stmt->bindValue(':crd', $data['cria_data']);
//        $stmt->bindValue(':crp', $data['cria_ip']);
//        $stmt->bindValue(':cri', $data['cria_dispositivo']);
//        $stmt->bindValue(':cri', $data['status']);
//        $stmt->execute();
//
//        if ($stmt->rowCount() > 0) {
//            //return 'Usuário(a) inserido com sucesso!';
//            throw new \Exception(self::$success_usuarioinseridocomsucesso);
//        } else {
//            throw new \Exception(self::$fail_usuariofalhaaoinserir);
//        }
//        
        
        
    }
    
    
    
        
    /**
     * define padrao base64UrlDecode para o JWT.io
     */
    private static function solicitacaoBase64Decode($data)
    {
        // Decodificar $data para string Base64
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
        //return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
    }
    

 
    /**
     * Decodifica a chave encripitada no padrao JWT.io e retorna os dados do payload
     */
    public static function keydecode($data)
    {
        $sgmtoken = explode('.', $data['sgmtoken']);
        //$header = $sgmtoken[0];
        $payload = $sgmtoken[1];
        //$sign = $sgmtoken[2];
        $dadoundecoder = self::solicitacaoBase64Decode($payload);
        return $dadoundecoder;
    }

    
    
}

