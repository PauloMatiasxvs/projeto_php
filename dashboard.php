<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Lógica de logout
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Define a página atual baseada no parâmetro "page"
$page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

// Lógica para exclusão de informações
if (isset($_GET['delete_stat'])) {
    $id = $_GET['delete_stat'];
    try {
        $stmt = $pdo->prepare("DELETE FROM estatisticas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: dashboard.php?page=inicio"); // Redireciona após excluir
        exit();
    } catch (PDOException $e) {
        die("Erro ao excluir estatística: " . $e->getMessage());
    }
}

// Lógica para adicionar uma nova informação
if (isset($_POST['add_stat'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    if (!empty($titulo) && !empty($descricao)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO estatisticas (titulo, descricao) VALUES (:titulo, :descricao)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->execute();
            header("Location: dashboard.php?page=inicio"); // Redireciona após adicionar
            exit();
        } catch (PDOException $e) {
            die("Erro ao adicionar informação: " . $e->getMessage());
        }
    }
}

try {
    // Consulta para obter os usuários (somente se a página for "usuarios")
    if ($page === 'usuarios') {
        $stmt = $pdo->query("SELECT id, nome, email FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Consulta para obter as estatísticas (somente se a página for "inicio")
    if ($page === 'inicio') {
        $stmt = $pdo->query("SELECT * FROM estatisticas");
        $estatisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            height: 100vh;
            color: #333;
        }
        /* Barra lateral */
        .sidebar {
            width: 250px;
            background: #34495e;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100%;
        }
        .sidebar h2 {
            text-align: center;
            padding: 20px 0;
            background: #2c3e50;
            margin: 0;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #2c3e50;
            transition: background 0.3s;
        }
        .sidebar ul li:hover {
            background: #2c3e50;
        }
        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
        }
        .sidebar .logout {
            text-align: center;
            padding: 15px;
            background: #e74c3c;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .sidebar .logout:hover {
            background: #c0392b;
        }
        /* Área principal */
        .main {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
        }
        .main header {
            background: #2980b9;
            padding: 15px;
            border-radius: 8px;
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
        .main h2 {
            margin-bottom: 20px;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2980b9;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #eaf6fd;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .edit, .delete {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
        }
        .edit {
            background-color: #27ae60;
        }
        .edit:hover {
            background-color: #1e8449;
        }
        .delete {
            background-color: #e74c3c;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
            margin-top: 10px;
            display: inline-block;
            width: 100px;
            margin-left: auto;
            text-align: center;
        }
        .delete:hover {
            background-color: #c0392b;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
        .estatisticas > div {
            margin-bottom: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .add-statistics {
            background: #27ae60;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-statistics:hover {
            background: #1e8449;
        }
        .add-statistics-container {
            background: #ecf0f1;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Painel Admin</h2>
        <ul>
            <li><a href="dashboard.php?page=inicio">Início</a></li>
            <li><a href="dashboard.php?page=usuarios">Usuários</a></li>
            <li><a href="dashboard.php?page=relatorios">Relatórios</a></li>
            <li><a href="dashboard.php?page=configuracoes">Configurações</a></li>
        </ul>
        <a href="dashboard.php?logout=true" class="logout">Sair</a>
    </div>

    <div class="main">
        <?php if ($page === 'inicio'): ?>
            <header>
                <h1>Bem-vindo ao Painel de Administração</h1>
            </header>
            <h2>Resumo do Sistema</h2>
            <p>Adicione aqui informações resumidas, como estatísticas ou gráficos.</p>

            <div class="add-statistics-container">
                <h3>Adicionar Nova Informação</h3>
                <form action="dashboard.php?page=inicio" method="POST">
                    <input type="text" name="titulo" placeholder="Título da Informação" required><br><br>
                    <textarea name="descricao" placeholder="Descrição" required></textarea><br><br>
                    <button type="submit" name="add_stat" class="add-statistics">Adicionar</button>
                </form>
            </div>

            <div class="estatisticas">
                <h3>Informações Adicionadas</h3>
                <?php foreach ($estatisticas as $estatistica): ?>
                    <div>
                        <h4><?php echo $estatistica['titulo']; ?></h4>
                        <p><?php echo $estatistica['descricao']; ?></p>
                        <a href="dashboard.php?page=inicio&delete_stat=<?php echo $estatistica['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir esta informação?')">Excluir</a>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'usuarios'): ?>
            <header>
                <h1>Gerenciamento de Usuários</h1>
            </header>
            <h2>Lista de Usuários</h2>
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
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo $usuario['nome']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td>
                                <div class="actions">
                                    <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="edit">Editar</a>
                                    <a href="deletar.php?id=<?php echo $usuario['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php elseif ($page === 'relatorios'): ?>
            <header>
                <h1>Relatórios</h1>
            </header>
            <p>Página de relatórios em construção...</p>

        <?php elseif ($page === 'configuracoes'): ?>
            <header>
                <h1>Configurações</h1>
            </header>
            <p>Página de configurações em construção...</p>
        <?php endif; ?>
    </div>
</body>
</html>

