<?php
/**
 * Teste de Upload - Diagnóstico
 */

session_start();

header('Content-Type: application/json');

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Não autenticado'
    ]);
    exit;
}

// Informações de diagnóstico
$diagnostico = [
    'php_version' => phpversion(),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'files_received' => isset($_FILES) ? count($_FILES) : 0,
    'files_data' => $_FILES,
    'upload_dir' => '../../uploads/equipa/',
    'upload_dir_exists' => is_dir('../../uploads/equipa/'),
    'upload_dir_writable' => is_writable('../../uploads/equipa/'),
];

echo json_encode([
    'success' => true,
    'diagnostico' => $diagnostico
], JSON_PRETTY_PRINT);
?>
