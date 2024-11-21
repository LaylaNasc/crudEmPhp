<?php 

function enviarArquivo($error, $size, $name, $tmp_name) {

    if($error)
        die("Falha ao enviar arquivo");

    if($size > 2097152) 
        die("Arquivo muito grande! Max: 2MB");

    $pasta = "arquivos/";
    $nomeDoArquivo = $name;
    $novoNomeDoArquivo = uniqid();
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != 'png')
        die("Tipo de arquivo não aceito!");
    
    $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
    $deuCerto = move_uploaded_file($tmp_name, $path);
        if($deuCerto) {
            return $path;
        } else
            return false;

}


?>