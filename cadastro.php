<?php
session_start();
require_once 'conexao.php';

// Adicionando cabeçalhos para permitir requisições CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Verifica se o usuário está logado
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php"); // Redireciona para o dashboard caso esteja logado
    exit();
}

// Lógica de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os dados foram enviados via API (JSON)
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data) {
        $nome = $data['nome'] ?? null;
        $email = $data['email'] ?? null;
        $senha = isset($data['senha']) ? password_hash($data['senha'], PASSWORD_DEFAULT) : null;
    } else {
        // Ou, caso contrário, pega os dados do formulário HTML
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $senha = isset($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;
    }

    // Valida os campos obrigatórios
    if (!$nome || !$email || !$senha) {
        echo json_encode(["error" => "Todos os campos são obrigatórios."]);
        exit();
    }

    try {
        // Verifica se o e-mail já está cadastrado
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(409); // Conflito
            echo json_encode(["error" => "Este e-mail já está cadastrado."]);
            exit();
        } else {
            // Cadastra o novo usuário
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            if ($stmt->execute()) {
                // Redireciona para login no caso de formulário
                if (!$data) {
                    header("Location: index.php");
                    exit();
                }

                // Ou responde sucesso no caso de API
                http_response_code(201); // Criado
                echo json_encode(["success" => true, "message" => "Usuário cadastrado com sucesso!"]);
                exit();
            } else {
                http_response_code(500); // Erro interno do servidor
                echo json_encode(["error" => "Erro ao cadastrar o usuário."]);
                exit();
            }
        }
    } catch (PDOException $e) {
        http_response_code(500); // Erro interno do servidor
        echo json_encode(["error" => "Erro ao acessar o banco de dados: " . $e->getMessage()]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
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
        input[type="text"], input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            background: #0f3460;
            color: #eaeaea;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
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
    </style>
</head>
<body>
    <form method="POST" action="cadastro.php">
        <h2>Crie sua conta</h2>
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" required>

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" required>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>