<?php
/**
 * API - Listar PDFs existentes na pasta
 * Para permitir seleção de PDFs já enviados via FTP
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

try {
    // Diretório de PDFs enviados via FTP
    $uploadDir = '../../uploads/catalogo/pdfs-ftp/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Listar todos os PDFs
    $pdfs = [];
    $files = scandir($uploadDir);
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
            $filePath = $uploadDir . $file;
            $pdfs[] = [
                'filename' => $file,
                'path' => '/backoffice/uploads/catalogo/pdfs-ftp/' . $file,
                'size' => filesize($filePath),
                'size_mb' => round(filesize($filePath) / 1024 / 1024, 2),
                'date' => date('Y-m-d H:i:s', filemtime($filePath))
            ];
        }
    }
    
    // Ordenar por data (mais recente primeiro)
    usort($pdfs, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $pdfs
    ]);

} catch (Exception $e) {
    error_log("List PDFs Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao listar PDFs'
    ]);
}
?>
