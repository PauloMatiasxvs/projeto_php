<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        try {
            // Consulta para verificar o usuário
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica a senha
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario'] = $usuario['nome'];
                header("Location: dashboard.php"); // Redireciona para o painel
                exit();
            } else {
                echo "Usuário ou senha inválidos!";
            }
        } catch (PDOException $e) {
            echo "Erro ao autenticar: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
