<?php

class APIView{
    public function response($data, $status){
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        echo json_encode($data);
    }
    private function _requestStatus($code){
        $status = array(
            200 => "OK", //La solicitud ha tenido éxito.
            201 => "Created",//La solicitud ha tenido éxito y se ha creado un nuevo recurso como resultado de ello.
            400 => "Bad request", //el servidor no pudo interpretar la solicitud dada una sintaxis inválida.
            404 => "Not found",// El servidor no pudo encontrar el contenido solicitado.
            500 => "Internal Server Error", //El servidor ha encontrado una situación que no sabe cómo manejarla.
        );
        return (isset($status[$code]))? $status[$code] : $status[500];
    }
}

?>