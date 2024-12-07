<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Validação de campos vazios
    if (empty($nome) || empty($email) || empty($senha)) {
        echo "Erro: Todos os campos (nome, email e senha) devem ser preenchidos!";
        exit();
    }

    // Hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        if ($stmt->execute()) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    } catch (PDOException $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
}
?>

