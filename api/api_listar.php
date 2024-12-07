<?php
$r = rand(0,100);
echo $r.'<BR>';

// Abre a conexão
include 'conexao.php';

// String de consulta da tabela de usuários
$sql = "SELECT * FROM projetologin.usuarios";

// Executar consulta e colocar os dados no objeto $resultado
$resultado = $conn->query($sql);

// Criar um array para armazenar os dados
$dados = array();

// Testa se há dados
if ($resultado->num_rows > 0)
{
    // Percorrer os resultados e adicionar ao array
    while($linha = $resultado->fetch_assoc())
    {
        //Armazena cada linha da tabela usuário em item do array $data
        $dados[] = $linha;
    }

    // Codificar o array como JSON
    $json_dados = json_encode($dados);

    echo ($json_dados);

// Fechar a Conexão
$conn->close();
}
else
{
    echo "Tabela vazia";
}