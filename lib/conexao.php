<?php

$host = "localhost:3307";
$db = "crud_clientes";
$user = "root";
$pass = "";

$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno) {
    die("Falha na conexão");
}

function formatarData($data) {
    return implode('/', array_reverse(explode('-', $data)));
} 

function formatarTelefone($telefone) {
        $ddd = substr($telefone, 0, 2);
        $parte1 = substr($telefone, 2, 5);
        $parte2 = substr($telefone, 7);
        return "($ddd) $parte1-$parte2";
    
} 