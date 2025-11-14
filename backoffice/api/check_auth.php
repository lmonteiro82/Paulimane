<?php
/**
 * API para verificar autenticação
 * Paulimane Backoffice
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

session_start();

require_once '../config/database.php';

try {
    // Verificar se existe sessão ativa
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'authenticated' => false,
            'message' => 'Não autenticado'
        ]);
        exit;
    }

    // Verificar se a sessão não expirou (24 horas)
    if (isset($_SESSION['login_time'])) {
        $session_duration = time() - $_SESSION['login_time'];
        if ($session_duration > 86400) { // 24 horas em segundos
            session_destroy();
            
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'authenticated' => false,
                'message' => 'Sessão expirada'
            ]);
            exit;
        }
    }

    // Verificar se utilizador ainda está ativo na BD
    $db = getDBConnection();
    
    $stmt = $db->prepare("
        SELECT ID, Nome, Email, Ativo, Imagem 
        FROM Utilizador 
        WHERE ID = :id 
        LIMIT 1
    ");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $user = $stmt->fetch();

    if (!$user || $user['Ativo'] != 1) {
        session_destroy();
        
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'authenticated' => false,
            'message' => 'Utilizador inativo'
        ]);
        exit;
    }

    // Autenticado com sucesso
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'authenticated' => true,
        'user' => [
            'id' => $user['ID'],
            'email' => $user['Email'],
            'nome' => $user['Nome'],
            'imagem' => $user['Imagem'] ?? ''
        ]
    ]);

} catch (Exception $e) {
    error_log("Check Auth Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'authenticated' => false,
        'message' => 'Erro ao verificar autenticação'
    ]);
}
?>
