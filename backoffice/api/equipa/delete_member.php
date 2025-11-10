<?php
require_once '../../config/database.php';

if (!isset($_GET['id'])) {
    die('ID não fornecido');
}

$id = (int)$_GET['id'];

try {
    $db = getDBConnection();
    
    // Buscar imagem para deletar
    $stmt = $db->prepare("SELECT Imagem FROM Equipa WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    $membro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($membro && file_exists('../../' . $membro['Imagem'])) {
        unlink('../../' . $membro['Imagem']);
    }
    
    // Deletar da BD
    $stmt = $db->prepare("DELETE FROM Equipa WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    echo "Membro eliminado com sucesso!<br>";
    echo "<a href='list_all.php'>Voltar</a>";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
