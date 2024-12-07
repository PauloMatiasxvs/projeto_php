<?php
$r = rand(0, 100);

// Abre conexão
include 'conexao.php';

// Obtém o ID da requisição
$id = $_GET['id']; // O ID que será usado para exclusão

// Preparando a query com parâmetros para exclusão
$stmt = $conn->prepare("DELETE FROM projetologin.usuarios WHERE id = ?");
$stmt->bind_param("i", $id); // "i" para inteiro, pois estamos utilizando ID (que é número)

// Executando a query
$stmt->execute();

// Verificando se a exclusão foi bem-sucedida
if ($stmt->affected_rows > 0) {
    $resultado = 1; // Executado com sucesso
} else {
    $resultado = 0; // Nenhum registro foi excluído (talvez o usuário não exista)
}

// Retornando o resultado
echo($resultado);

// Fechando a conexão
$stmt->close();
$conn->close();
