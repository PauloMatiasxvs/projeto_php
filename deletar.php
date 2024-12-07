<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o ID foi passado na URL
if (!isset($_GET['id'])) {
    die('ID do usuário não fornecido');
}

$id = $_GET['id'];

try {
    // Deleta o usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        header("Location: dashboard.php"); // Redireciona para a lista de usuários
        exit();
    } else {
        echo "Erro ao excluir usuário.";
    }
} catch (PDOException $e) {
    die("Erro ao acessar o banco de dados: " . $e->getMessage());
}
?>
