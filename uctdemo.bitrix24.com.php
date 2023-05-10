<?php

/*Array de Uras do sistema chatbot*/

$config = array(

    'BOT_ID' => '440',
    'CLIENT_ID' => 'q0lkcm5cqdlcbuiuukkvszz13n7itpsk',
    'URL' => 'https://uctdemo.bitrix24.com/rest/426/k6hh18oim2n9cil6/',
    /*Mensagens do Atendimento MOZ Positivo*/
    /*Mensagens Iniciais*/
    'SAUDACAO' => "Seja bem-vindo ao canal *MOZ POSITIVO*, seu novo recomeço!💛🧡💛",
    'CAPTURA_PERGUNTA' => "Agradecemos seu contato😊",
    'CAPTURA_NOME' => 'Qual seu nome?👀',
    'CAPTURA_EMAIL' => 'E seu e-mail?📧',
    'CAPTURA_NEGATIVADO' => ', Agradeço! hoje voce possui negativação?👀',
    'OPCAO_NEG1' => '*1.* _Sim_',
    'OPCAO_NEG2' => '*2.* _Não_',
     /*Falando com NEGATIVADO*/
    'CAPTURA_VALORNEG' => 'Qual o valor das suas negativações?',
    'OPCOE_OBJ' => '*Qual seu objetivo hoje?*',
    'OPCAO_OBJ1' =>'*1* _Limpar o Nome_',
    'OPCAO_OBJ2' =>'*2* _Aumentar o score_',
    'OPCAO_OBJ3' =>'*3* _Conseguir Credito_',
    'OPCAO_OBJ4' =>'*4* _Financiar Imovel_',
    'OPCAO_OBJ5' =>'*5* _Serviço CNPJ_',
    'VIDEO' => 'Gostaria de receber um video explicativo?',
    'VIDEO_1' => '*1.* _Sim_',
    'VIDEO_2' => '*2.* _Não_',
    'LINK' => 'https://abre.ai/mozpositivo',
    'ENC_VIDEO' => 'Faz sentido esse video para você?',
    'OPCAOVIDEO1' => '*1* _Sim_',
    'OPCAOVIDEO2' => '*2* _Com toda Certeza_',
    'OPCAOVIDEO3' => '*3* _Não no momento_',
    'ENC1' => 'Irei lhe direcionar para um dos nossos atendentes! Aguarde um instante que em breve você será atendido! 😉',
    'ENC2' => 'A Moz Positivo agradece se contatoAgradecemos seu contato',
     /*Falando com QUEM NÃO POSSUI DIVIDA*/
    'CONSULTA1' => 'Legal, vamos realizar uma consulta?🤔',
    'OPCAO_CONS1' => '*1.* _Sim_',
    'OPCAO_CONS2' => '*2.* _Não_',
    'MENS_ENC1' => 'A *MOZ POSITIVO* agradeçe seu contato! Nos vemos em breve! 😉',
    'VALORES' => '*1-* _SERASA R$ 30,00_',
    'VALORES2' =>'*2-* _BOA VISTA R$ 15,00_',
    'VALORES3' => '*3-* _COMPLETA R$ 45,00_',
    'DOC' => '*Qual documento deseja consultar?*',
    'DOC_OPC1' => 'CPF',
    'DOC_OPC2' => 'CNPJ',
    'DOC_OPC3' => 'AMBOS',    
    'CAPTURA_CPF' => 'Qual nº do *Documento* para consulta?🤔',
    'ENC_CONS' => 'Legal, segue dados de pagamento da consulta, em alguns minutos você receberá sua consulta por aqui',    
    'FORA_ENC2' => ' PIX: *financeiro@mozpositivo.com.br*',
    'FORA_ENC2' => 'Após envio do comprovante, sua consulta chegará até voce! 😉. A *MOZ Positivo* agradece!',
        


    // //Grupos
    // 'COMERCIAL' => '148',
    // 'BITRIX' => '150',
    // 'TELEFONIA' => '152',
    // 'FINANCEIRO' => '154',

    //* Pesquisar funcionários
    // 'SRC_USER' => 'https://uctdemo.bitrix24.com/rest/12/z2fsucx4hgkw2im3/',

    // // Criar leads
    // 'LEADADD' => 'https://uctdemo.bitrix24.com/rest/80/jzn1fxpd12bq7yj8/',

    // 'USERID' => '12',


    // 'URA_ERRO' => "Erro",
);

function menu_ura($mensagem = NULL, $atual = NULL, $metodos, $conn, $config, $row = null)
{


    if (mb_strpos($mensagem, '=== Outgoing message, author: Bitrix24 (')) {

        //Captura do nome do usuário que enviou a mensagem pelo Wazzup

        $pos = strpos($mensagem, '(');
        $nome = substr($mensagem, ($pos + 1), -1);
        $pos = strpos($nome, ')');
        $nome = substr($nome, 0, $pos);
        //--------------------------------

        file_put_contents(__DIR__ . '/imbot.log', "\n" . 'Mensagem do Wazzup detectada com o nome: ' . $nome, FILE_APPEND);

        $IDUSER = getUserByName($config['SRC_USER'], 'user.search.json?', $nome);

        file_put_contents(__DIR__ . '/imbot.log', "\n" . "ID: $IDUSER encontrado!", FILE_APPEND);

        controler_bot($config['URL'], $metodos['CRIARLEAD'], array(

            'BOT_ID=' . $config['BOT_ID'] . '&',
            'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
            'CHAT_ID=' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
            'LEAVE=Y'  . '&',
            'TRANSFER_ID=' . $IDUSER

        ));
    }
}