<?php 

include('lib/conexao.php');

if(!isset($_SESSION))
    session_start();

if(!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    die();
}
 

$id = $_SESSION['usuario'];

$sqlClientes =  "SELECT * FROM clientes WHERE id != '$id'";
$queryClientes = $mysqli->query($sqlClientes) or die($mysqli->error);
$numClientes = $queryClientes->num_rows;
?>

<?php
if (!isset($_SESSION)) session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-3">Lista de clientes</h1>
        <?php if($_SESSION['admin']) { ?>
        <p class="text-muted fw-bold mb-1 fs-4">Estes são os clientes cadastrados no sistema:</p>
        <div class="text-end mb-3">
            <a href="cadastraCliente.php" class="btn btn-dark">Cadastrar um Novo Cliente</a>
        </div>

        <?php } ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>É Admin?</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Nascimento</th>
                        <th>Telefone</th>
                        <th>Data da cobrança</th>
                        <?php if($_SESSION['admin']){ ?>
                        <th>Ações</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($numClientes == 0) { ?>
                    <tr>
                        <td colspan="<?php if($_SESSION['admin']) echo 9; else echo 8; ?>" class="text-center">Nenhum cliente foi cadastrado</td>
                    </tr>
                    <?php } else {
                        while($clientes = $queryClientes->fetch_assoc()) {
                            $telefone = "Não informado";
                            if(!empty($clientes['telefone'])) {
                                $telefone = formatarTelefone($clientes['telefone']);
                            }
                            $nascimento = "Não informado";
                            if(!empty($clientes['nascimento'])) {
                                $nascimento = formatarData($clientes['nascimento']);
                            }
                            $dataCadastro = date("d/m/Y H:i", strtotime($clientes['data'])); 
                        ?>
                        <tr>
                            <td><?php echo $clientes['id']; ?></td>
                            <td><?php if($clientes['admin']) echo "Sim"; else echo "Não"; ?></td>
                            <td><img height="30" src="<?php echo $clientes['foto']; ?>" alt="" class="img-thumbnail"></td>
                            <td><?php echo $clientes['nome']; ?></td>
                            <td><?php echo $clientes['email']; ?></td>
                            <td><?php echo $nascimento; ?></td>
                            <td><?php echo $telefone; ?></td>
                            <td><?php echo $dataCadastro; ?></td> 
                            <?php if($_SESSION['admin']) { ?>
                                <td class="d-flex gap-2">
                                    <a href="atualizarCliente.php?id=<?php echo $clientes['id']; ?>" class="btn btn-dark btn-sm">Atualizar</a>
                                    <a href="deletarCliente.php?id=<?php echo $clientes['id']; ?>" class="btn btn-danger btn-sm">Deletar</a>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
        <div class="text-end">
            <a href="logout.php" class="btn btn-dark">Sair</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-rMO8IuQs2KnQeOKLAxRS1Or7ZrMfbaoknxPZy05E67hNsRZn/ERDpxTLFvTY/lTZ" crossorigin="anonymous"></script>
</body>
</html>