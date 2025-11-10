<?php
/**
 * API Pública - Listar Produtos por Categoria
 * Endpoint público para exibir produtos no site
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../backoffice/config/database.php';

try {
    $db = getDBConnection();
    
    // Verificar se foi passado um ID de categoria
    $categoriaId = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
    
    if ($categoriaId) {
        // Buscar produtos de uma categoria específica
        $stmt = $db->prepare("
            SELECT p.ID, p.Imagem, p.Nome, p.Descricao, p.CategoriaID, c.Nome as CategoriaNome
            FROM Produtos p
            LEFT JOIN Categoria c ON p.CategoriaID = c.ID
            WHERE p.CategoriaID = :categoriaId
            ORDER BY p.ID DESC
        ");
        $stmt->execute([':categoriaId' => $categoriaId]);
    } else {
        // Buscar todos os produtos
        $stmt = $db->query("
            SELECT p.ID, p.Imagem, p.Nome, p.Descricao, p.CategoriaID, c.Nome as CategoriaNome
            FROM Produtos p
            LEFT JOIN Categoria c ON p.CategoriaID = c.ID
            ORDER BY p.ID DESC
        ");
    }
    
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("API Produtos Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar produtos'
    ]);
}
?>
