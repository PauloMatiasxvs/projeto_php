<?php
$r = rand(0,100);

// Abre conexão
require_once '../conexao.php';

$email = $_GET['email'];
$senha = $_GET['senha'];
$nome = $_GET['nome'];

// Preparando a query com parâmetros
$stmt = $pdo->prepare("INSERT INTO projetologin.usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->execute([$nome, $email, $senha]); // Passa os parâmetros diretamente

// Obtendo o ID inserido (o método varia de acordo com o banco de dados)
$last_id = $pdo->lastInsertId();

// Verificando se a inserção foi bem-sucedida
if ($stmt->rowCount() > 0) {
    $resultado = $last_id;
} else {
    $resultado = 0;
}

// Retornando o resultado
echo json_encode(['success' => $resultado > 0, 'error' => $resultado === 0 ? 'Erro ao cadastrar usuário.' : '']);
?>
