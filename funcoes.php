<?php

use LDAP\Result;

function controler_bot($url = NULL, $metodo = NULL, $parametros){

if(($metodo != NULL) && is_array($parametros) && ($url != NULL)){

    $response = @file_get_contents($url . $metodo . implode($parametros));
    json_decode($response, true);

    file_put_contents(__DIR__ . '/imbot.log', "\n" . $response, FILE_APPEND);
    file_put_contents(__DIR__ . '/imbot.log', "\n" . $url . $metodo . implode($parametros), FILE_APPEND);
}
}

function getUserByName($url = NULL, $metodo = NULL, $name){
    if(($metodo != NULL) && ($url != NULL)){

        try{
        $response = @file_get_contents($url . $metodo . '?NAME=' . $name);
        $response = json_decode($response);
        $resultado = $response->result;

        }catch(Exception $err){
            
            file_put_contents(__DIR__ . '/imbot.log', "\n" . $err->getMessage(), FILE_APPEND);

        }finally{

            file_put_contents(__DIR__ . '/imbot.log', "\n" . $url . $metodo . '?NAME=' . $name, FILE_APPEND);
            file_put_contents(__DIR__ . '/imbot.log', "\n" . $resultado[0]->ID . ' NAME=' .$name, FILE_APPEND);
        
        }
    }

    return $resultado[0]->ID;
}

function updateUra($row, $msg, $conn){

    file_put_contents(__DIR__ . '/imbot.log', "\n" . $row['URA'] . $msg . ";", FILE_APPEND);

    $query = "UPDATE conversas SET `URA` = " . "'" . $row['URA'] . $msg . ";" . "'". " WHERE `ID` = '" . $row['ID'] . "'";    
    
    return mysqli_query($conn, $query);
}


function popUra($row, $conn){
    
    // $ura = array_pop($row['URA']);
    $ura = substr($row['URA'], 0, -4);
    $query = "UPDATE conversas SET `URA` = " . "'" . $ura . ";" . "'". " WHERE `ID` = '" . $row['ID'] . "'";    
    
    return mysqli_query($conn, $query);

}