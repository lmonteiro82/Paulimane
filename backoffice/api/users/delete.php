<?php
/**
 * API - Eliminar Utilizador
 * Paulimane Backoffice
 */

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
require_once '../../config/check_access.php';

// Verificar nível de acesso (apenas nível 3 pode gerenciar utilizadores)
requireAPIAccess(3);

try {
    // Ler dados do request
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validar ID
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID do utilizador não fornecido'
        ]);
        exit;
    }
    
    $id = (int)$input['id'];
    
    // Não permitir eliminar o próprio utilizador
    if ($id == $_SESSION['user_id']) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Não pode eliminar o seu próprio utilizador'
        ]);
        exit;
    }
    
    $db = getDBConnection();
    
    // Verificar se utilizador existe
    $stmt = $db->prepare("SELECT ID FROM Utilizador WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Utilizador não encontrado'
        ]);
        exit;
    }
    
    // Eliminar utilizador
    $stmt = $db->prepare("DELETE FROM Utilizador WHERE ID = :id");
    $stmt->execute([':id' => $id]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Utilizador eliminado com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Delete User Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao eliminar utilizador'
    ]);
}
?>
