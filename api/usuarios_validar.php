<?php
$r = rand(0,100);
//echo $r.'<BR>';

// Abre conexão
require_once '../conexao.php';

$email = $_GET['email'];
$senha = $_GET['senha'];

// String de consulta da tabela de usuários
$sql = "SELECT count(*) AS passou FROM projetologin.usuarios WHERE email = ? AND senha = ?";

// executar consulta e coloca os dados no objeto $resultado
$stmt = $pdo->prepare($sql);
$stmt->execute([$email, $senha]);

// Criar um array para armazenar os dados
$dados = array();

// testa se há dados
if ($stmt->rowCount() > 0) {
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    // Codificar o array como JSON
    $json_dados = json_encode($linha['passou']);

    echo ($json_dados);
} else {
    echo "Tabela vazia";
}
?>
