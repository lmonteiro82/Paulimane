<?php
/**
 * API - Upload de PDF para Categoria
 */

session_start();

// Configurar limites para servidor Linux
@ini_set('upload_max_filesize', '50M');
@ini_set('post_max_size', '60M');
@ini_set('max_execution_time', '300');
@ini_set('max_input_time', '300');
@ini_set('memory_limit', '256M');

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
    error_log("Upload PDF - FILES: " . print_r($_FILES, true));
    
    // Verificar se o arquivo foi enviado
    if (!isset($_FILES['pdf'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum arquivo PDF foi enviado (campo não encontrado)'
        ]);
        exit;
    }
    
    if ($_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        $errorMsg = 'Erro no upload: ';
        $uploadMaxSize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        
        switch ($_FILES['pdf']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errorMsg .= "Ficheiro muito grande. Limites do servidor: upload_max_filesize={$uploadMaxSize}, post_max_size={$postMaxSize}. Por favor, use um PDF menor que 10MB.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMsg .= 'Upload incompleto';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMsg .= 'Nenhum ficheiro enviado';
                break;
            default:
                $errorMsg .= 'Erro desconhecido (código: ' . $_FILES['pdf']['error'] . ')';
        }
        
        error_log("Upload PDF Error - Código: {$_FILES['pdf']['error']}, Limites: upload_max={$uploadMaxSize}, post_max={$postMaxSize}");
        
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $errorMsg
        ]);
        exit;
    }
    
    $file = $_FILES['pdf'];
    
    // Log do tamanho do ficheiro e limites do servidor
    $uploadMaxSize = ini_get('upload_max_filesize');
    $postMaxSize = ini_get('post_max_size');
    error_log("Upload PDF - Tamanho do ficheiro: " . $file['size'] . " bytes (" . round($file['size']/1024/1024, 2) . "MB)");
    error_log("Upload PDF - Limites PHP: upload_max_filesize={$uploadMaxSize}, post_max_size={$postMaxSize}");
    
    // Validar tipo de arquivo
    $allowedTypes = ['application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    error_log("Upload PDF - MIME type detectado: " . $mimeType);
    
    if (!in_array($mimeType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Apenas ficheiros PDF são permitidos. Tipo detectado: ' . $mimeType
        ]);
        exit;
    }
    
    // Validar tamanho (máximo 10MB para compatibilidade com servidores)
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        $fileSizeMB = round($file['size'] / 1024 / 1024, 2);
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "PDF muito grande ({$fileSizeMB}MB). Máximo permitido: 10MB. Por favor, comprima o PDF antes de fazer upload."
        ]);
        exit;
    }
    
    // Gerar nome único para o arquivo
    $extension = 'pdf';
    $fileName = uniqid('catalogo_', true) . '.' . $extension;
    
    // Diretório de upload
    $uploadDir = '../../uploads/catalogo/pdfs/';
    $uploadPath = $uploadDir . $fileName;
    
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
    
    // Mover arquivo para o diretório de upload
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        error_log("Erro ao mover ficheiro de {$file['tmp_name']} para {$uploadPath}");
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao guardar PDF. Verifique permissões da pasta.'
        ]);
        exit;
    }
    
    error_log("PDF guardado com sucesso: " . $uploadPath);
    
    // Retornar caminho relativo do arquivo
    $relativePath = '/backoffice/uploads/catalogo/pdfs/' . $fileName;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'PDF enviado com sucesso',
        'path' => $relativePath,
        'filename' => $fileName
    ]);

} catch (Exception $e) {
    error_log("Upload PDF Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao fazer upload do PDF'
    ]);
}
?>
