<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

require_once 'conexao.php';

try {
    $sql = "SELECT * FROM usuarios";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?>!</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['idPrimária'] ?></td>
                    <td><?= $usuario['nome'] ?></td>
                    <td><?= $usuario['emailÍndice'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $usuario['idPrimária'] ?>">Editar</a>
                        <a href="deletar.php?id=<?= $usuario['idPrimária'] ?>">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
