<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário de login
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Consultar no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['id']; // Armazena o ID do usuário na sessão
            header("Location: dashboard.php"); // Redireciona para o dashboard após login
            exit();
        } else {
            $erro = "Usuário ou senha inválidos!";
        }
    } catch (PDOException $e) {
        die("Erro ao acessar o banco de dados: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a2e;
            color: #eaeaea;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background: #16213e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #00d4ff;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
            color: #a6a6a6;
        }
        input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="email"], input[type="password"] {
            background: #0f3460;
            color: #eaeaea;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 5px #00d4ff;
        }
        button {
            background-color: #00d4ff;
            color: #1a1a2e;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0096c7;
        }
        .error {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        p {
            font-size: 14px;
            margin-top: 15px;
        }
        p a {
            color: #00d4ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        p a:hover {
            color: #0096c7;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Bem-vindo de volta</h2>
        <?php if (isset($erro)) { echo "<p class='error'>$erro</p>"; } ?>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" required>

        <button type="submit">Entrar</button>
        <p>Não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    </form>
</body>
</html>
