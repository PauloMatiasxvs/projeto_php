<?php
$r = rand(0,100);
//echo $r.'<BR>';

// Abre conexão
require_once '../conexao.php';

// String de consulta da tabela de usuários
$sql = "SELECT * FROM projetologin.usuarios";

// executar consulta e coloca os dados no objeto $resultado
$resultado = $pdo->query($sql);

// Criar um array para armazenar os dados
$dados = array();

// testa se há dados
if ($resultado->rowCount() > 0) {
    // Percorrer os resultados e adicionar ao array
    while($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
        // Armazena cada linha da tabela usuario em item do array $data
        $dados[] = $linha;
    }

    // Codificar o array como JSON
    $json_dados = json_encode($dados);
    echo ($json_dados);
} else {
    echo "Tabela vazia";
}
?>
