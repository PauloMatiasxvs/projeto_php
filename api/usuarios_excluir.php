<?php
$r = rand(0,100);

// Abre conexão
require_once '../conexao.php';

$id = $_GET['id']; // O login ou identificador que será usado para exclusão

// Preparando a query com parâmetros para exclusão
$stmt = $pdo->prepare("DELETE FROM projetologin.usuarios WHERE id = ?");
$stmt->execute([$id]); // Executa a query com o parâmetro

// Verificando se a exclusão foi bem-sucedida
if ($stmt->rowCount() > 0) {
    $resultado = 1; // Excluído com sucesso
} else {
    $resultado = 0; // Nenhum registro excluído (talvez o usuário não exista)
}

// Retornando o resultado
echo($resultado);
?>
