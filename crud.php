<?php
require_once 'conexao.php';

if (isset($_GET['acao']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_GET['acao'] === 'deletar') {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        header('Location: home.php');
        exit();
    }

    if ($_GET['acao'] === 'editar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $query = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':nome' => $nome, ':email' => $email, ':id' => $id]);
        header('Location: home.php');
        exit();
    }
}
?>
