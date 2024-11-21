<?php
if (isset($_POST['email']) && isset($_POST['senha'])) {
    session_start();
    include('lib/conexao.php');

    $email = $mysqli->escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sqlCode = "SELECT * FROM clientes WHERE email = '$email'";
    $sqlQuery = $mysqli->query($sqlCode) or die($mysqli->error);

    if ($sqlQuery->num_rows == 0) {
        echo "Email incorreto";
    } else {
        $usuario = $sqlQuery->fetch_assoc();

        if (!password_verify($senha, $usuario['senha'])) {
            echo "Senha incorreta!";
        } else {
            $_SESSION['usuario'] = $usuario['id'];
            $_SESSION['admin'] = $usuario['admin'];
            header("Location: clientes.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Entrar</h1>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha" class="form-control">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark w-100">Entrar</button>
                            </div>
                        </form>  
                        <div class="text-center mt-3">
                            <a href="logout.php" class="text-decoration-none text-dark">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>

