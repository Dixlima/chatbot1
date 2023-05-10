<?php

/*Logica do ChatBot*/

if ($_REQUEST['event'] != 'ONIMBOTJOINCHAT') {

    if ($_REQUEST['data']['PARAMS']['MESSAGE'] != '=== SYSTEM WZ === The client has not installed the app or has linked it to another number.') {

        include($_REQUEST['auth']['domain'] . '.php');

        include('conexao.php');

        include('metodos.php');

        include('funcoes.php');

        $query = "SELECT * FROM conversas WHERE CHAT_ID = '" . $_REQUEST['data']['PARAMS']['TO_CHAT_ID'] . "'AND URL = '" . $_REQUEST['auth']['domain'] . "'";

        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        /*INICIO DO CHATBOT SAUDAÇÃO*/

        if (mysqli_num_rows($result) <= 0) {

            $query = "INSERT INTO conversas(`CHAT_ID`, `URL`, `URA`) VALUES('" . $_REQUEST['data']['PARAMS']['CHAT_ID'] . "' ," . "'" . $_REQUEST['auth']['domain'] . "'" . ", '0;')";

            $result = mysqli_query($conn, $query);

            controler_bot($config['URL'], $metodos['ENVIAR'], array(

                'BOT_ID=' . $config['BOT_ID'] . '&',
                'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                'MESSAGE=' . $config['SAUDACAO']

            ));

            sleep(1);

            /*Agradecimento*/

            controler_bot($config['URL'], $metodos['ENVIAR'], array(

                'BOT_ID=' . $config['BOT_ID'] . '&',
                'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                'MESSAGE=' . $config['CAPTURA_PERGUNTA']

            ));

            sleep(1);

            /*Identificação de cliente*/


            controler_bot($config['URL'], $metodos['ENVIAR'], array(

                'BOT_ID=' . $config['BOT_ID'] . '&',
                'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                'MESSAGE=' . $config['CAPTURA_NOME']

            ));

            return true;

              /*E-mail do Cliente*/
        } else {


            if ($row['NOME'] == '' && $row['ETAPA'] != '1') {

                $query = "UPDATE conversas SET `NOME` = " . "'" . $_REQUEST['data']['PARAMS']['MESSAGE'] . "'" . ", `ETAPA` = '1'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);
                file_put_contents(__DIR__ . '/imbot.log', "\n" . $row['ID'], FILE_APPEND);
                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['CAPTURA_EMAIL']
                ));

                return true;

                /*Pergunta Negativado e tomada de decisão por etapa*/
            } elseif ($row['ETAPA'] == '1' && $row['EMAIL'] == '') {

                $query = "UPDATE conversas SET `EMAIL` = " . "'" . $_REQUEST['data']['PARAMS']['MESSAGE'] . "'" . ", `ETAPA` = '2'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $row['NOME'] . $config['CAPTURA_NEGATIVADO']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCAO_NEG1']



                ));

                sleep(1);
                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCAO_NEG2']



                ));

                return true;


                /*SOLICITAÇÃO DA DUVIDA*/
            } elseif ($row['ETAPA'] == '2' && $row['NEGATIVADO'] == '') {

                $query = "UPDATE conversas SET `NEGATIVADO` = " . "'" . $_REQUEST['data']['PARAMS']['MESSAGE'] . "'" . ", `ETAPA` = '3'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);


                $query = "SELECT * FROM conversas WHERE CHAT_ID = '" . $_REQUEST['data']['PARAMS']['TO_CHAT_ID'] . "'AND URL = '" . $_REQUEST['auth']['domain'] . "'";

                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
            }


            if ($row['NEGATIVADO'] == '1' && $row['ETAPA'] == '3') {

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['CAPTURA_VALORNEG']

                    
                ));

                $query = "UPDATE conversas SET `ETAPA` = '4'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);
               
                

             return true;

            } elseif ($row['ETAPA'] == '4' && $row['NEGATIVADO'] == '1') {

                $query = "UPDATE conversas SET `ETAPA` = '5'" . '`VALOR_NEGATIVADO`= ' . $_REQUEST['data']['PARAMS']['MESSAGE'] . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);
                


                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCOE_OBJ']



                ));

                sleep(1);


        
                    controler_bot($config['URL'], $metodos['ENVIAR'], array(

                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['OPCAO_OBJ1']
    
    
    
                    ));
                    sleep(1);

                    controler_bot($config['URL'], $metodos['ENVIAR'], array(

                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['OPCAO_OBJ2']
    
    
    
                    ));
                    sleep(1);
                    controler_bot($config['URL'], $metodos['ENVIAR'], array(

                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['OPCAO_OBJ3']
    
    
    
                    ));
                    sleep(1);
                    controler_bot($config['URL'], $metodos['ENVIAR'], array(

                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['OPCAO_OBJ4']
    
    
    
                    ));
                    sleep(1);
                    controler_bot($config['URL'], $metodos['ENVIAR'], array(

                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['OPCAO_OBJ5']
    
    
    
                    ));
                    $query = "UPDATE conversas SET `ETAPA` = '5'" . " WHERE `ID` = '" . $row['ID'] . "'";

                    $result = mysqli_query($conn, $query);
                   
                    

                 return true;

               

            } elseif ($row['ETAPA'] == '5' && $row['NEGATIVADO'] == '1') {

                $query = "UPDATE conversas SET `ETAPA` = '6'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);
                

                    controler_bot($config['URL'], $metodos['ENVIAR'], array(
    
                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['VIDEO']
    
                    ));

                    sleep(1);

                    controler_bot($config['URL'], $metodos['ENVIAR'], array(
    
                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['VIDEO_1']
                    ));

                    sleep(1);

                    controler_bot($config['URL'], $metodos['ENVIAR'], array(
    
                        'BOT_ID=' . $config['BOT_ID'] . '&',
                        'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                        'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                        'MESSAGE=' . $config['VIDEO_2']
    
                    ));
                  
                   
            

                } elseif ($row['ETAPA'] == '6' && $row['NEGATIVADO'] == '1' &&  $_REQUEST['data']['PARAMS']['MESSAGE']== '1') {

                    $query = "UPDATE conversas SET `ETAPA` = '7'" . " WHERE `ID` = '" . $row['ID'] . "'";
    
                    $result = mysqli_query($conn, $query);
                    
            
                        controler_bot($config['URL'], $metodos['ENVIAR'], array(
        
                            'BOT_ID=' . $config['BOT_ID'] . '&',
                            'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                            'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                            'MESSAGE=' . $config['LINK']
        
                        ));

                    sleep(15);


                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['ENC_VIDEO']

                ));
                sleep(1);
                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCAOVIDEO1']

                ));
                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCAOVIDEO2']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['OPCAOVIDEO3']

                ));


            } elseif ($row['ETAPA'] == '7' && $row['NEGATIVADO'] == '1' &&  $_REQUEST['data']['PARAMS']['MESSAGE'] == '2') {

                $query = "UPDATE conversas SET `ETAPA` = '8'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);


                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['ENC1']

                ));


            } elseif ($row['NEGATIVADO'] == '2'&& $row['ETAPA'] == 3) {

                $query = "UPDATE conversas SET `ETAPA` = '4'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['CONSULTA1']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['VALORES']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['VALORES2']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['VALORES3']

                ));

                sleep(1);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['CAPTURA_CPF']

                ));
                
return true;

            } elseif ($row['ETAPA'] == '4' && $row['NEGATIVADO'] == '2') {

                $query = "UPDATE conversas SET `ETAPA` = '4'" . " WHERE `ID` = '" . $row['ID'] . "'";

                $result = mysqli_query($conn, $query);

                controler_bot($config['URL'], $metodos['ENVIAR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CLIENT_ID=' . $config['CLIENT_ID'] . '&',
                    'DIALOG_ID=chat' . $_REQUEST['data']['PARAMS']['CHAT_ID'] . '&',
                    'MESSAGE=' . $config['FORA_ENC2']

                ));

return true;
                
            }

            if ($_REQUEST['data']['USER']['IS_EXTRANET'] == 'Y') {



                menu_ura($_REQUEST['data']['PARAMS']['MESSAGE'], $ura[3], $metodos, $conn, $config, $row);
            } else {

                controler_bot($config['URL'], $metodos['SAIR'], array(

                    'BOT_ID=' . $config['BOT_ID'] . '&',
                    'CHAT_ID=' . $_REQUEST['data']['PARAMS']['CHAT_ID']

                ));
            }
        }
    }
}
     

/*date_default_timezone_set('America/São Paulo');
$t = date('h:i');
if (($t >'07:30') && ($t < '17:30')){}
else {}