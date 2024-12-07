<?php
$host = 'projetologin.mysql.dbaas.com.br'; 
$usuario = 'projetologin';
$senha = 'Odiado@12';
$banco = 'projetologin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
