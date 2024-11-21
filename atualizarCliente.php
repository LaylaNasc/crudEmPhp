<?php

if(!isset($_SESSION))
    session_start();

if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: clientes.php");
    die();
}

include('lib/conexao.php');
include('lib/upload.php');

$id = intval($_GET['id']);
function limparTexto($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

if (count($_POST) > 0) {

    $erro = false;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nascimento = $_POST['nascimento'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $sqlCodeExtra = "";
    $admin = $_POST['admin'];

    if (!empty($senha)) {
        if (strlen($senha) < 6 && strlen($senha) > 16) {
            $erro = "A senha tem que ter entre 6 e 16 caracteres";
        } else {
            $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
            $sqlCodeExtra = "senha = '$senhaCriptografada', ";
        }
    }

    if (empty($nome)) {
        $erro = "Preencha o nome";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o email";
    }

    if (!empty($nascimento)) {
        $pedacos = explode('/', $nascimento);
        if (count($pedacos) == 3) {
            $nascimento = implode('-', array_reverse($pedacos));
        } else {
            $erro = "O padrão de data é dia/mes/ano.";
        }
    }

    if (!empty($telefone)) {
        $telefone = limparTexto($telefone);
        if (strlen($telefone) != 11) {
            $erro = "O padrão para telefone é (00)00000-0000";
        }
    }

    if(isset($_FILES['foto'])) {
        $arq = $_FILES['foto'];
        $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
        if($path == false)
            $erro = "Falha ao enviar arquivo. Tente novamente";
        else
            $sqlCodeExtra .= " foto = '$path', ";
    
        if(!empty($_POST['fotoAntiga']))
            unlink($_POST['fotoAntiga']);

    }

    if($erro) {
        echo "<p><b>ERRO: $erro</b></p>";
    } else {

        $sql_code = "UPDATE clientes
        SET nome = '$nome', 
        email = '$email',
        $sqlCodeExtra
        telefone = '$telefone',
        nascimento = '$nascimento',
        admin = '$admin'
        WHERE id = '$id'";
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
        if($deu_certo) {
            echo "<p><b>Cliente atualizado com sucesso!!!</b></p>";
            unset($_POST);
        }
    }

}


$sqlClientes = "SELECT * FROM clientes WHERE id = $id";
$queryCliente = $mysqli->query($sqlClientes) or die($mysqli->error);
$cliente = $queryCliente->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <a href="clientes.php" class="btn btn-dark mb-4">Voltar para a lista</a>
        <form enctype="multipart/form-data" method="POST" action="" class="row g-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome:</label>
                <input value="<?php echo $cliente['nome']; ?>" name="nome" type="text" class="form-control" id="nome">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">E-mail:</label>
                <input value="<?php echo $cliente['email']; ?>" name="email" type="text" class="form-control" id="email">
            </div>
            <div class="col-md-6">
                <label for="nascimento" class="form-label">Data de Nascimento:</label>
                <input value="<?php if(!empty($cliente['nascimento'])) echo formatarData($cliente['nascimento']);?>" name="nascimento" type="text" class="form-control" id="nascimento">
            </div>
            <div class="col-md-6">
                <label for="senha" class="form-label">Senha:</label>
                <input name="senha" type="text" class="form-control" id="senha">
            </div>
            <?php if($cliente['foto']) { ?>
            <div class="col-12">
                <label class="form-label">Foto Atual:</label>
                <div class="d-flex align-items-center">
                    <img height="50" src="<?php echo $cliente['foto'];?>" alt="" class="img-thumbnail me-2">
                    <input name="fotoAntiga" value="<?php echo $cliente['foto'];?>" type="hidden">
                </div>
            </div>
            <?php } ?>
            <div class="col-md-6">
                <label for="novaFoto" class="form-label">Nova Foto:</label>
                <input name="foto" type="file" class="form-control" id="novaFoto">
            </div>
            <div class="col-md-6">
                <label for="telefone" class="form-label">Telefone:</label>
                <input value="<?php if(!empty($cliente['telefone'])) echo formatarTelefone($cliente['telefone']);?>" placeholder="(00)00000-0000" name="telefone" type="tel" class="form-control" id="telefone">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
