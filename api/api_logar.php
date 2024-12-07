<?php
$r = rand(0,100);
echo $r.'<BR>';

// Abre conexão
include 'conexao.php';

$email = $_GET['login'];
$senha = $_GET['senha'];

// String de consulta da tabela de emails cadastrados
$sql = "SELECT count (*) passou FROM projetologin.usuarios WHERE login = '$email' and senha = '$senha';";

file_put_contents('sql.txt', $sql);

// Executar consulta e colocar os dados no objeto $resultado
$resultado = $conn->query($sql);

// Criar um array para armazenar os dados
$dados = array();

// Testa se há dados
if ($resultado->num_rows > 0)
{
    $linha = $resultado->fetch_assoc();

    // Codificar o array como JSON
    $json_dados = json_encode ($linha['passou']);

    echo ($json_dados);

}
else
{
    echo "Tabela vazia";
}
// Fechando a conexão
$conn->close();