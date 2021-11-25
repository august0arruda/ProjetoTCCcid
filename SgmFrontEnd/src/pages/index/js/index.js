/**
 * -------------------------------------------------------
 * PROJETO: Sapopemba
 * DESCRICAO: Sistema de Telessaude do estado do Amazonas
 * Cliente: Governo do Estado do Amazonas - SUS - UEA
 * Solicitante: Governo do Estado do Amazonas - SUS - UEA
 * -------------------------------------------------------
 * Descricao do arquivo:
 * login de acesso para modulo de cadastro
 * -------------------------------------------------------
 * Desenvolvimento:
 * NAP UEA
 * Augusto Arruda 
 * Analista TI - Desenvolvedor Full Stack
 * Email: augustoarruda@uea.edu.br
 * Cel: (092) 991848979
 * Manaus, 24 de março de 2020.
 */


function testecookie(){
    //testa dados dos Cookies
    alert(Cookies.get('sgmToken'));        
};



//funcao de animação ao processar
function anima_tabelasolicitacao(k){//a- ativa i- inativa
    if(k === 'a'){
        //start animacao de processamento
        $('.loading_tabelasolicitacao').css({display: 'block'});
    }else{
        //close animacao de processamento
        $('.loading_tabelasolicitacao').css({display: 'none'});
    }
   
};



//nova solicitacao
function novasolicitacao(){//envia post que sobe os arquivos a serem anexados pelo calouro
    $.ajax({
        url : "./src/pages/index/solicitacao.html",
        type : 'post',
        data : {
            novasolicitacao: 'nova_solicitacao'
        }
    })
    .done(function(msg){//retorno apos sucesso
        //close animacao de processamento
        $('#div_novasolicitacao').html(msg);//recepciona o valores encontrados em html
        $("#modal_novasolicitacao").modal('show');//sobe o modal
    })
    .fail(function( msg){//retorno apos falha
        alert(msg);
    }); 
};



function readerallsolicitacao(){
    
    
//    alert($('#userid').val());
    
    //retorna cookie instanciado
    var SgmToken = Cookies.get('sgmToken');
    //start animacao de processamento
    anima_tabelasolicitacao('a'); //ativa animacao
    $.ajax({
        //url: "http://192.168.0.18:8081/ProjetoTCCcid/SgmBackEnd/public_html/api/solicitacao/reader",
        url: "http://192.168.0.18:8081/ProjetoTCCcid/SgmBackEnd/public_html/api/solicitacao/reader_solicitante="+userdataId,
        method: 'POST',
        headers: {//Recepciona os dados do token no Web Storage da API
            //'Access-Control-Allow-Origin': '*',//liberar acesso a qualquer dominio inclusive o local 
            //'Content-Type': 'application/json; application/x-www-form-urlencoded',//padrao header tipo JSON
            'Authorization': 'Bearer ' + SgmToken,
            'Aplicacao': 'Sistema de Gestao Municipal',
            'Modulo': 'Administrador',
            'POC_TCC': 'Puc Minas',
            'Autor': 'Augusto Arruda'
            },
        data: {
            sgmtoken: SgmToken
        }

    })
    .done(function(msg){
        //retorno > sucess ou error
        if(msg.status === 'sucess'){//Seta os dados do token no Web Storage da API
            var new_row, cols, deftime, solicitante_, solicitante; 
            //loop dos dados
            $.each(msg.data, function(index, array_buscacadastrousuario){
                
                solicitante_ = JSON.parse(array_buscacadastrousuario['solicitante']);
                solicitante = solicitante_[0].nome;;
                
                new_row = $('<tr>');
                //cols += '<td style="color: #808080;">'+index+'</td>'; //referencia cinza
                cols += '<td class="coluna_tbody ctb_0">'+array_buscacadastrousuario['id']+'</td>'; //referencia cinza
                cols += '<td class="coluna_tbody ctb_1">'+array_buscacadastrousuario['processo']+'</td>';
                cols += '<td class="coluna_tbody ctb_1">'+array_buscacadastrousuario['tiposervico']+'</td>';
                cols += '<td class="coluna_tbody ctb_1">'+array_buscacadastrousuario['descricao']+'</td>';
                cols += '<td class="coluna_tbody ctb_1">'+array_buscacadastrousuario['data_solicitacao']+'</td>';
                cols += '<td class="coluna_tbody ctb_1">'+solicitante+'</td>';
                cols += '<td class="coluna_tbody ctb_1">'+array_buscacadastrousuario['status']+'</td>';
                cols += ('</tr>');
                new_row.append(cols);
                $('#tabelasolicitacao').append(new_row);
                
            });

            instanciar_tabelasolicitacao();
        }else if(msg.status === 'error'){
            alert(msg.status);
        }else {
            alert('Falha no retorno');
        } 

    })
    .fail(function(msg){//retorno apos falha
        //informes do erro    
        alert('Falha ao acessar token: '+JSON.stringify(msg));
        //atualiza pagina
        location.reload();
    }); 
    //start animacao de processamento
    anima_tabelasolicitacao('i'); //ativa animacao
}




//instancia o plugin de tabelas DataTable
function instanciar_tabelasolicitacao(){
    //caracteres webdings
    //https://graphemica.com/%E2%8C%95
    //&#128269;  
    //&#8981;
    $('#tabelasolicitacao').DataTable({
        //Idioma - portugues-Brasil by Augusto
        language: { 
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Visualizar _MENU_ resultados",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "         &#128270;",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        "searching": true, //ativa-desativa busca
        "lengthChange": true, //ativza-desativa numeros de linhas a serem apresentadas
        "paging": true, //ativa-desativa paginacao
        "info": true, //ativa-desativa informacao de linhas
        "responsive": true, //ativa-desativa Responsividade
        "destroy": true, //destroi a tabela que entrou anteriormente
        "order": [ 0, "desc" ], // define a ordenacao tipo default da tabela
        //"order": [[ 1, "asc" ], [ 10, "desc" ], [ 5, "desc" ]], // define a ordenacao tipo default da tabela
        //"order": [[ 10, "desc" ], [ 5, "desc" ]], // define a ordenacao tipo default da tabela
        'columnDefs': [// definicoes de clunas é usado para definir aparência e comportamento da primeira coluna 
            {
                'targets': 0,//define se o alvo do clique
                'visible': true// define se o alvo (id) e visivel ou nao
            }
    //        {
    //            'targets': [11,12,13],//define a coluna que vai ficar os checkboxes
    //            'data': 0,
    //            'checkboxes': false
    //        },
    //        {
    //          'render': function (data, type, full, meta){
    //             return '<input type="checkbox" name="id[]" value="' + $('<div/>').text('ssss' ).html() + '">';
    //        }
    //        }
            ]
    });

};



//ajuste responsivel do plugin datatables
$(window).resize(function(){
    /*recarregar a instancia*/
    //recarrega os dados toda vez que e redimencionado usando recurso "destroy: true" no plugin DataTable
    readerallsolicitacao();
});


$(document).ready(function(){//sobe as funcoes ao carregar a pagina
    
    /*Carga da barra de menus*/
    var urlmenu = "./src/pages/menu/menu.html";
    $("#div_urlmenu").load(urlmenu); 
    
    //hint com mouse over ou tootltip
    $('[data-toggle="tooltip"]').tooltip();
    
    
//    /*
//     * Usando throw junto com try e catch, pode-se controlar o fluxo do programa
//     * e gerar mensagens de erro personalizadas.
//     */
//    try {
//        //se vazio retrona err caso contrario executa readerallsolicitacao
//        if($('#userid').val() !== "") throw readerallsolicitacao();//variavel userid setado pelo arquivo menu.js
//    } catch(err) {
//        //executa o bloco se userid for vazio
//        alert('Atualizar!');
//        //atualiza pagina
//        //location.reload();
//    } finally {
//        //executa o bloco independente dos resultados anteriores
//    }
       
    var deftime;
    deftime = 400;
    setTimeout(
        function(){//executa a funcao do temporizador
            //variavel userid setado pelo arquivo menu.js
            readerallsolicitacao();
        }
    , deftime); //definicao do temporizador - em milisegundos
    

});
   