<?php
/**
 * API - Upload de Imagem da Equipa
 */

session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
    // Debug: log dos dados recebidos
    error_log("Upload Equipa - FILES: " . print_r($_FILES, true));
    
    // Verificar se ficheiro foi enviado
    if (!isset($_FILES['imagem'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhuma imagem foi enviada (campo não encontrado)'
        ]);
        exit;
    }
    
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        $errorMsg = 'Erro no upload: ';
        switch ($_FILES['imagem']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errorMsg .= 'Ficheiro muito grande';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMsg .= 'Upload incompleto';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMsg .= 'Nenhum ficheiro enviado';
                break;
            default:
                $errorMsg .= 'Erro desconhecido (código: ' . $_FILES['imagem']['error'] . ')';
        }
        
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $errorMsg
        ]);
        exit;
    }
    
    $file = $_FILES['imagem'];
    
    // Log do tamanho do ficheiro e limites do servidor
    $uploadMaxSize = ini_get('upload_max_filesize');
    $postMaxSize = ini_get('post_max_size');
    error_log("Upload Equipa - Tamanho do ficheiro: " . $file['size'] . " bytes (" . round($file['size']/1024/1024, 2) . "MB)");
    error_log("Upload Equipa - Limites PHP: upload_max_filesize={$uploadMaxSize}, post_max_size={$postMaxSize}");
    
    // Validar tipo de ficheiro
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de ficheiro não permitido. Use JPG, PNG, GIF ou WEBP'
        ]);
        exit;
    }
    
    // Validar tamanho (máx 10MB)
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        $fileSizeMB = round($file['size'] / 1024 / 1024, 2);
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Imagem muito grande ({$fileSizeMB}MB). Máximo 10MB"
        ]);
        exit;
    }
    
    // Gerar nome único para o ficheiro
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('equipa_', true) . '.' . $extension;
    
    // Caminho completo - de /backoffice/api/equipa/ para /backoffice/uploads/equipa/
    $uploadDir = '../../uploads/equipa/';
    $uploadPath = $uploadDir . $filename;
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            error_log("Erro ao criar diretório: " . $uploadDir);
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao criar pasta de upload'
            ]);
            exit;
        }
    }
    
    // Verificar se diretório é gravável
    if (!is_writable($uploadDir)) {
        error_log("Diretório não é gravável: " . $uploadDir);
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Pasta de upload sem permissão de escrita'
        ]);
        exit;
    }
    
    // Mover ficheiro
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        error_log("Erro ao mover ficheiro de {$file['tmp_name']} para {$uploadPath}");
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao guardar imagem. Verifique permissões da pasta.'
        ]);
        exit;
    }
    
    error_log("Imagem guardada com sucesso: " . $uploadPath);
    
    // Retornar caminho absoluto a partir da raiz do domínio
    $relativePath = '/backoffice/uploads/equipa/' . $filename;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'path' => $relativePath,
        'message' => 'Imagem enviada com sucesso'
    ]);

} catch (Exception $e) {
    error_log("Upload Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar upload'
    ]);
}
?>
