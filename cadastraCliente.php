<?php

    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION['usuario']) || !$_SESSION['usuario']) {
        header("Location: index.php");
        die();
    }   


    function limparTexto($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }
    
    
    if(count ($_POST) > 0) {

        include('lib/conexao.php');
        include('lib/upload.php');

        
       $erro = false;
       $nome = $_POST['nome'];
       $email = $_POST['email'];
       $nascimento = $_POST['nascimento'];
       $telefone = $_POST['telefone'];       
       $senhaDescriptografada = $_POST['senha'];
       $admin = $_POST['admin'];

        if(strlen($senhaDescriptografada) < 6 && strlen($senhaDescriptografada) > 16) {
            $erro = "A senha tem que ter entre 6 e 16 caracteres"; 
        }
    
        if(empty($nome)) {
            $erro = "Preencha o nome";
        }
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Preencha o email";
        }
        
        if(!empty($nascimento)) {
            $pedacos = explode('/', $nascimento);
            if(count($pedacos) == 3) {
                $nascimento = implode('-', array_reverse($pedacos));
            } else {
                $erro = "O padrão de data é dia/mes/ano.";
            }
        }

        if(!empty($telefone)) {
            $telefone = limparTexto($telefone);
            if(strlen($telefone)!= 11)
            $erro = "O padrão para telefone é (00)00000-0000";
        }

        $path = ""; 
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
            
            if($path === false) {
                $erro = "Falha ao enviar arquivo. Tente novamente.";
            }
        }


        if($erro){
            echo "<p><b>Erro: $erro<?b></p>";
        } else{
            $senha = password_hash($senhaDescriptografada, PASSWORD_DEFAULT);
            $sql_code = "INSERT INTO clientes (nome, email, senha, telefone, nascimento, data, foto, admin) VALUES ('$nome', '$email', '$senha', '$telefone', '$nascimento', NOW(), '$path', $admin)";
            $deuCerto = $mysqli->query($sql_code) or die($mysqli->error);
            if($deuCerto){
                echo "<p><b>Cliente Cadastrado!<?b></p>";
                unset($_POST); 
            } 
        }

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex flex-column min-vh-100">
<header class="bg-dark text-white py-3">
    <div class="container text-center">
        <h1 class="h4 mb-0">Sistema de Gerenciamento</h1>
    </div>
</header>
    <div class="container mt-5">
    <h1 class="text-center mb-3">Cadastro de Clientes</h1>
        <a href="clientes.php" class="btn btn-dark mb-4">Voltar para a lista</a>
        <form enctype="multipart/form-data" method="POST" action="cadastraCliente.php" class="row g-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome:</label>
                <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome'];?>" name="nome" type="text" class="form-control" id="nome">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email:</label>
                <input value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" name="email" type="email" class="form-control" id="email">
            </div>
            <div class="col-md-6">
                <label for="nascimento" class="form-label">Data de Nascimento:</label>
                <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento'];?>" name="nascimento" type="text" class="form-control" id="nascimento">
            </div>
            <div class="col-md-6">
                <label for="senha" class="form-label">Senha:</label>
                <input value="<?php if(isset($_POST['senha'])) echo $_POST['senha'];?>" name="senha" type="password" class="form-control" id="senha">
            </div>
            <div class="col-md-6">
                <label for="foto" class="form-label">Foto do Usuário:</label>
                <input name="foto" type="file" class="form-control" id="foto">
            </div>
            <div class="col-md-6">
                <label for="telefone" class="form-label">Telefone:</label>
                <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone'];?>" placeholder="(00)00000-0000" name="telefone" type="tel" class="form-control" id="telefone">
            </div>
            <div class="col-12">
                <label class="form-label">Tipo:</label>
                <div class="form-check">
                    <input name="admin" value="1" type="radio" class="form-check-input" id="admin">
                    <label class="form-check-label" for="admin">ADMIN</label>
                </div>
                <div class="form-check">
                    <input name="admin" value="0" checked type="radio" class="form-check-input" id="cliente">
                    <label class="form-check-label" for="cliente">Cliente</label>
                </div>
            </div>
            <div class="col-12">
                <button name="submit" class="btn btn-dark">Enviar</button>
            </div>
        </form>
    </div>
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="text-center">
            <p class="mb-0">Desenvolvido por Layla</p>
        </div>
    </footer>
</body>
</html>
