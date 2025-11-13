<?php
/**
 * Página de Debug - Verificar Sessão e Nível
 */
session_start();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Sessão</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #252526;
            padding: 20px;
            border-radius: 8px;
        }
        h1 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        h2 {
            color: #569cd6;
            margin-top: 30px;
        }
        .info {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #4ec9b0;
        }
        .warning {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #ce9178;
            color: #ce9178;
        }
        .error {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #f48771;
            color: #f48771;
        }
        .success {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #4ec9b0;
            color: #4ec9b0;
        }
        pre {
            background: #1e1e1e;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0e639c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #1177bb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug - Informações da Sessão</h1>
        
        <h2>📋 Dados da Sessão PHP</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="success">
                ✅ Utilizador autenticado!
            </div>
            <div class="info">
                <strong>ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?><br>
                <strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['nome'] ?? 'N/A'); ?><br>
                <strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?><br>
                <strong>Nível:</strong> <?php echo isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 'NÃO DEFINIDO'; ?>
                <?php if (!isset($_SESSION['user_nivel'])): ?>
                    <span class="error">⚠️ PROBLEMA: Nível não está definido na sessão!</span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="error">
                ❌ Utilizador NÃO autenticado!
            </div>
        <?php endif; ?>
        
        <h2>🔐 Permissões Baseadas no Nível</h2>
        <?php
        $nivel = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 0;
        
        if ($nivel === 0) {
            echo '<div class="error">❌ Nível não definido - Sem permissões</div>';
        } else {
            echo '<div class="info">';
            echo '<strong>Nível Atual:</strong> ' . $nivel . '<br><br>';
            
            echo '<strong>Páginas Permitidas:</strong><br>';
            $paginas = [
                1 => ['textos', 'equipa', 'clientes'],
                2 => ['textos', 'equipa', 'clientes', 'categorias', 'destaques'],
                3 => ['utilizadores', 'textos', 'equipa', 'clientes', 'categorias', 'destaques']
            ];
            
            $permitidas = $paginas[$nivel] ?? [];
            foreach ($permitidas as $pagina) {
                echo '✅ ' . $pagina . '.php<br>';
            }
            
            echo '<br><strong>Páginas BLOQUEADAS:</strong><br>';
            $todas = ['utilizadores', 'textos', 'equipa', 'clientes', 'categorias', 'destaques'];
            $bloqueadas = array_diff($todas, $permitidas);
            foreach ($bloqueadas as $pagina) {
                echo '❌ ' . $pagina . '.php<br>';
            }
            echo '</div>';
        }
        ?>
        
        <h2>🗄️ Verificar Base de Dados</h2>
        <?php
        require_once 'config/database.php';
        
        if (isset($_SESSION['user_id'])) {
            try {
                $db = getDBConnection();
                $stmt = $db->prepare("SELECT ID, Nome, Email, Nivel, Ativo FROM Utilizador WHERE ID = :id");
                $stmt->execute([':id' => $_SESSION['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    echo '<div class="info">';
                    echo '<strong>Dados na Base de Dados:</strong><br>';
                    echo 'ID: ' . $user['ID'] . '<br>';
                    echo 'Nome: ' . htmlspecialchars($user['Nome']) . '<br>';
                    echo 'Email: ' . htmlspecialchars($user['Email']) . '<br>';
                    echo 'Nível: ' . ($user['Nivel'] ?? 'NULL') . '<br>';
                    echo 'Ativo: ' . ($user['Ativo'] ? 'Sim' : 'Não') . '<br>';
                    echo '</div>';
                    
                    // Comparar com sessão
                    $nivel_bd = isset($user['Nivel']) ? (int)$user['Nivel'] : 0;
                    $nivel_sessao = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 0;
                    
                    if ($nivel_bd !== $nivel_sessao) {
                        echo '<div class="error">';
                        echo '⚠️ INCONSISTÊNCIA DETECTADA!<br>';
                        echo 'Nível na Base de Dados: ' . $nivel_bd . '<br>';
                        echo 'Nível na Sessão: ' . $nivel_sessao . '<br>';
                        echo '<br><strong>SOLUÇÃO:</strong> Faça logout e login novamente!';
                        echo '</div>';
                    } else {
                        echo '<div class="success">';
                        echo '✅ Nível na sessão está sincronizado com a base de dados!';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error">❌ Utilizador não encontrado na base de dados!</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error">❌ Erro ao consultar base de dados: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        
        <h2>📦 Sessão Completa (Raw)</h2>
        <pre><?php print_r($_SESSION); ?></pre>
        
        <h2>🔧 Ações</h2>
        <a href="index.php" class="btn">← Voltar ao Backoffice</a>
        <a href="api/logout.php" class="btn" style="background: #a31515;">🚪 Fazer Logout</a>
    </div>
</body>
</html>
