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
namespace App\Controllers;
/* alias/import */
use App\Models\User;
use App\Models\Auth;

class UserController
{
    private static $fail_verbonaoautorizado = "Verbo não autorizado!";
    private static $fail_metodo = "Método não implementado!";
    private static $fail_key = "Chave não validada!";
    private static $fail_key_expirada = "Chave expirada!";

    //Captura o metodo detectado pela variavel global GET
    public function get()
    {
        return self::$fail_verbonaoautorizado;
    }

    //Captura o metodo detectado pela variavel global POST
    public function post($metodo) 
    {
        //checa se usuario esta autenticado
//        if (Auth::checkAuth($_POST) === true)
        if (Auth::checkAuthNoform($_POST) === true)//verifica se a chave JWT.io e válida sem o uso de formulario
        {
            //$testePost = var_dump($_POST);
            $form_data = $_POST;//captura formulario enviado via POST
            $_metodo = explode('=', $metodo); //tratamento de dados reader=2000001
            $metodo_ = $_metodo[0];
            $metodo_id = $_metodo[1];

            switch ($metodo_)
            {
                case 'create':
                    //ex: 172.17.101.255/ProjetosNAP/sapopemba-telessaude/App_public/api/user/reader
                    if(!isset($metodo_id)){return User::insert($form_data);}else{return self::$fail_metodo;}
                    break;
                case 'reader':
                    //172.17.101.255/ProjetosNAP/sapopemba-telessaude/App_public/api/user/reader=21000001
                    if(!isset($metodo_id)){return User::selectAll();}else{return User::select($metodo_id);}
                    break;
                case 'update':
                    if(!isset($metodo_id)){return $mensagem;}else{return self::$fail_metodo;}
                    break;
                case 'delete':
                    if(!isset($metodo_id)){return $mensagem;}else{return self::$fail_metodo;}
                    break;
                    
                    
                case 'keydecode'://Decodifica a chave encripitada no padrao JWT.io e retorna os dados do payload
                    if(!isset($metodo_id)){return  User::keydecode($form_data)   ;}else{return self::$fail_metodo;}
                    break;
                    
                    
                default:
                    //throw new \Exception(self::$testePost);
                    throw new \Exception(self::$fail_metodo);
            }	

        }
        else if (Auth::checkAuth($_POST) === 'expired')
        {
            throw new \Exception(self::$fail_key_expirada);
        }


        else
        {
            throw new \Exception(self::$fail_key);
        }

    }

    //Captura o metodo detectado pela variavel global PUT
    public function put() 
    {
        return self::$fail_verbonaoautorizado;
    }

    //Captura o metodo detectado pela variavel global DELETE
    public function delete() 
    {
        return self::$fail_verbonaoautorizado;
    }

    //Captura o metodo detectado pela variavel global OPTIONS
    public function options() 
    {
        return self::$fail_verbonaoautorizado;
    }

    //Captura o metodo detectado pela variavel global PATCH
    public function patch() 
    {
        return self::$fail_verbonaoautorizado;
    }

}










//
//
//
//
//
//    /* Define namespace */
//    namespace App\Controllers;
//    /* alias/import */
//    use App\Models\User;
//    use App\Models\Auth;
//    
//
//    class UserController
//    {
//        private static $fail_verbo = "Verbo não implementado!";
//        private static $fail_metodo = "Método não implementado!";
//        private static $fail_key = "Chave não validada!";
//        private static $fail_key_expirada = "Chave expirada!";
//        
//        //Captura o metodo detectado pela variavel global GET
//        public function get()
//        {
//            return self::$fail_verbo;
//        }
//        
//        //Captura o metodo detectado pela variavel global POST
//        public function post($metodo) 
//        {
//            //checa se usuario esta autenticado
//            if (Auth::checkAuth() === true)
//            {
//                $form_data = $_POST;//captura formulario enviado via POST
//                $_metodo = explode('=', $metodo); //tratamento de dados reader=2000001
//                $metodo_ = $_metodo[0];
//                $metodo_id = $_metodo[1];
//               
//                switch ($metodo_)
//                {
//                    //ex: http://localhost/area_teste/api_rest_2021-master/public_html/api/user/create
//                    case 'create':
//                        if(!isset($metodo_id)){return User::insert($form_data);}else{return self::$fail_metodo;}
//                        break;
//                    //ex selectAll: http://localhost/area_teste/api_rest_2021-master/public_html/api/user/reader
//                    //ex select($metodo_id): http://localhost/area_teste/api_rest_2021-master/public_html/api/user/reader=2000001
//                    case 'reader':
//                        if(!isset($metodo_id)){return User::selectAll();}else{return User::select($metodo_id);}
//                        break;
//                    case 'update':
//                        if(!isset($metodo_id)){return $mensagem;}else{return self::$fail_metodo;}
//                        break;
//                    case 'delete':
//                        if(!isset($metodo_id)){return $mensagem;}else{return self::$fail_metodo;}
//                        break;
//                    default:
//                        throw new \Exception(self::$fail_metodo);
//                }	
//                
//            }
//            else if (Auth::checkAuth() === 'expired')
//            {
//                throw new \Exception(self::$fail_key_expirada);
//            }
//            else
//            {
//                throw new \Exception(self::$fail_key);
//            }
//            
//        }
//
//        //Captura o metodo detectado pela variavel global PUT
//        public function put() 
//        {
//            return self::$fail_verbo;
//        }
//        
//        //Captura o metodo detectado pela variavel global DELETE
//        public function delete() 
//        {
//            return self::$fail_verbo;
//        }
//        
//        //Captura o metodo detectado pela variavel global OPTIONS
//        public function options() 
//        {
//            return self::$fail_verbo;
//        }
//        
//        //Captura o metodo detectado pela variavel global PATCH
//        public function patch() 
//        {
//            return self::$fail_verbo;
//        }
//
//    }