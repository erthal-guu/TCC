<?php

$server ="localhost";
$user = "root";
$password = "";
$database = "gerenciador_agendas";

$connection =new mysqli($server,$user,$password,$database);

if ($connection->connect_error){
    die ('Erro de conexão'.$connection->connect_error);
}
?>