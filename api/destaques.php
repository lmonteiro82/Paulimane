<?php
/**
 * API Pública - Produtos em Destaque
 * Retorna os produtos em destaque para exibir na página inicial
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../backoffice/config/database.php';

try {
    $db = getDBConnection();
    
    // Buscar destaques (máximo 6)
    $stmt = $db->query("
        SELECT 
            ID,
            Nome,
            Descricao,
            Imagem
        FROM Destaques
        ORDER BY ID ASC
        LIMIT 6
    ");
    $destaques = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Garantir que os caminhos das imagens começam com /
    foreach ($destaques as &$item) {
        if (!empty($item['Imagem']) && $item['Imagem'][0] !== '/') {
            $item['Imagem'] = '/' . $item['Imagem'];
        }
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $destaques
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("API Destaques Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar produtos em destaque'
    ]);
}
?>
