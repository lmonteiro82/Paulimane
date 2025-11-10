<?php
/**
 * API - Listar Produtos em Destaque
 */

session_start();

header('Content-Type: application/json; charset=utf-8');

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Não autenticado'
    ]);
    exit;
}

require_once '../../config/database.php';

try {
    $db = getDBConnection();
    
    // Buscar produtos em destaque com JOIN
    $stmt = $db->query("
        SELECT 
            d.ID as DestaqueID,
            d.ProdutoID,
            p.Nome,
            p.Descricao,
            p.Imagem,
            c.Nome as CategoriaNome
        FROM Destaques d
        INNER JOIN Produtos p ON d.ProdutoID = p.ID
        LEFT JOIN Categoria c ON p.CategoriaID = c.ID
        ORDER BY d.ID ASC
    ");
    $destaques = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $destaques
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("List Destaques Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar produtos em destaque'
    ]);
}
?>
