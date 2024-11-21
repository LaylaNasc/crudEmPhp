<?php
if(isset($_POST['confirma'])) {

    include('lib/conexao.php');
    $id = intval($_GET['id']);

    $sqlClientes = "SELECT foto FROM clientes WHERE id = $id";
    $queryCliente = $mysqli->query($sqlClientes) or die($mysqli->error);
    $cliente = $queryCliente->fetch_assoc();

    $sql_code = "DELETE FROM clientes WHERE id = '$id'";
    $sqlQuery = $mysqli->query($sql_code) or die($mysqli->error);

    if($sqlQuery) { 
        
        if(!empty($cliente['foto']))
            unlink($cliente['foto']);
        
        ?>

        <h1>Cliente deletado com sucesso!</h1>
        <p> <a href="clientes.php">Clique aqui</a> e retorne para a lista de clientes.</p>
        <?php
        die();
    } 
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="text-dark">Tem certeza que deseja deletar esse cliente?</h1>
            <form action="" method="post" class="d-inline-block mt-4">
                <a href="clientes.php" class="btn btn-dark me-3">Não</a>
                <button name="confirma" type="submit" class="btn btn-danger">Sim</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
