<?php

class HttpCall{
  function curl_call($type, $url, $parametros, $tokenSession = null, $authorization = null, $upload_file = null){
    //primeiras validações de dados
    
    if(!is_array($parametros)){
      return ['status' => false, 'msg' => 'Formato de parâmetros inválidos'];
    }
    
    if($type != 'GET' && $type != 'POST'){
      return ['status' => false, 'msg' => 'Tipo de chamada inválida'];
    }
    
    //Execução da chamada
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    if($type == 'POST'){
      curl_setopt($ch, CURLOPT_POST, 1);

      if(sizeof($parametros) > 0)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
    }

    $array_header = [];

    if(!empty($tokenSession)){
      $array_header = array_merge($array_header, ["Content-Type: application/json"]);
      $array_header = array_merge($array_header, ["Authorization: $tokenSession"]);
    }

    if(sizeof($array_header) > 0)
      curl_setopt($ch, CURLOPT_HTTPHEADER, $array_header);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if(!empty($authorization)){
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      //string user:password
      curl_setopt($ch, CURLOPT_USERPWD, $authorization);
    }

    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
    $server_output = curl_exec($ch);

    if(curl_errno($ch)){
      return ['status' => false, 'msg' => 'Erro na chamada curl:'. curl_error($ch)];
    }
    
    curl_close($ch);

    return ['status' => true, 'result' => $server_output];
  }
}