<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do usuário não informado.");
}

$id = (int)$_GET['id'];

try {
    // Busca o usuário pelo ID
    $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar usuário: " . $e->getMessage());
}

// Verifica se o formulário foi enviado para atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao atualizar usuário: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
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
        input[type="text"], input[type="email"], button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="text"], input[type="email"] {
            background: #0f3460;
            color: #eaeaea;
        }
        input[type="text"]:focus, input[type="email"]:focus {
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
        .back {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #00d4ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .back:hover {
            color: #0096c7;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Editar Usuário</h2>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

        <button type="submit">Salvar Alterações</button>
        <a href="dashboard.php" class="back">Voltar</a>
    </form>
</body>
</html>
