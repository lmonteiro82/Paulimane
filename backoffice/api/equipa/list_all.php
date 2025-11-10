<?php
require_once '../../config/database.php';

header('Content-Type: text/html; charset=utf-8');

try {
    $db = getDBConnection();
    
    $stmt = $db->query("SELECT * FROM Equipa ORDER BY ID ASC");
    $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Todos os Membros da Equipa</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Imagem</th><th>Nome</th><th>Função</th><th>Ações</th></tr>";
    
    foreach ($membros as $m) {
        echo "<tr>";
        echo "<td>{$m['ID']}</td>";
        echo "<td><img src='../../{$m['Imagem']}' style='max-width:50px;'></td>";
        echo "<td>{$m['Nome']}</td>";
        echo "<td>{$m['Funcao']}</td>";
        echo "<td><a href='delete_member.php?id={$m['ID']}' onclick='return confirm(\"Eliminar?\")'>Eliminar</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
