<?php
/**
 * API - Obter Texto
 * Paulimane Backoffice
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

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
    // Validar chave
    if (!isset($_GET['chave']) || empty($_GET['chave'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Chave não fornecida'
        ]);
        exit;
    }
    
    $chave = $_GET['chave'];
    
    $db = getDBConnection();
    
    // Buscar texto
    $stmt = $db->prepare("SELECT Texto FROM Textos WHERE Chave = :chave LIMIT 1");
    $stmt->execute([':chave' => $chave]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'texto' => $result['Texto']
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Texto não encontrado'
        ]);
    }

} catch (Exception $e) {
    error_log("Get Texto Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao carregar texto',
        'debug' => $e->getMessage() // REMOVER EM PRODUÇÃO
    ]);
}
?>
