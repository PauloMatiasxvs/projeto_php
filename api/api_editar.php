<?php
$r = rand (0,100);

// Abre conexão
include 'conexao.php';

$email = $_GET['login']; // Login do usuário a ser alterado
$senha = $_GET['email']; // A nova senha
$nome = $_GET['nome']; // O novo nome

// Preparando a query com parâmetros para atualização
$stmt = $conn->prepare ("UPDATE projetologin.usuarios SET nome = ?, senha = ? WHERE login = ?");
$stmt->bind_param("sss", $nome, $senha, $email); // "sss" para 3 strings (nome, senha, login)

// Executando a query
$stmt->execute ();

// Verificando se a atualização foi bem-sucedida
if ($stmt->affected_rows > 0)
{
    $resultado = 1; // Atualização bem-sucedida
}
else
{
    $resultado = 0; // Nenhum registro foi alterado (talvez o login não exista)
}

// Retornando o resultado
echo($resultado);

// Fechando a conexão
$conn->close ();