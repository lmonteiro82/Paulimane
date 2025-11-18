<?php
/**
 * API de Produtos - Backoffice
 * CRUD completo de produtos
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

require_once '../config/database.php';

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    $db = getDBConnection();
    
    switch ($method) {
        case 'GET':
            // Listar todos os produtos com informações da categoria
            $stmt = $db->query("
                SELECT p.*, c.Nome as CategoriaNome 
                FROM Produtos p 
                LEFT JOIN Categoria c ON p.CategoriaID = c.ID 
                ORDER BY p.ID DESC
            ");
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $produtos
            ], JSON_UNESCAPED_UNICODE);
            break;
            
        case 'POST':
            // Criar novo produto
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $categoriaID = $_POST['categoria'] ?? 0;
            
            if (empty($nome) || empty($categoriaID)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Nome e categoria são obrigatórios']);
                exit;
            }
            
            // Upload da imagem
            $imagemPath = '';
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/produtos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $filename = 'produto_' . uniqid() . '.' . $extension;
                $uploadPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadPath)) {
                    $imagemPath = 'backoffice/uploads/produtos/' . $filename;
                }
            }
            
            $stmt = $db->prepare("INSERT INTO Produtos (Nome, Descricao, Imagem, CategoriaID) VALUES (:nome, :descricao, :imagem, :categoria)");
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':imagem' => $imagemPath,
                ':categoria' => $categoriaID
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Produto criado com sucesso',
                'id' => $db->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Atualizar produto
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
                exit;
            }
            
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $nome = $data['nome'] ?? '';
            $descricao = $data['descricao'] ?? '';
            $categoriaID = $data['categoria'] ?? 0;
            
            if (empty($nome) || empty($categoriaID)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Nome e categoria são obrigatórios']);
                exit;
            }
            
            $stmt = $db->prepare("UPDATE Produtos SET Nome = :nome, Descricao = :descricao, CategoriaID = :categoria WHERE ID = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':categoria' => $categoriaID,
                ':id' => $id
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Produto atualizado com sucesso'
            ]);
            break;
            
        case 'DELETE':
            // Apagar produto
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
                exit;
            }
            
            // Buscar imagem para apagar
            $stmt = $db->prepare("SELECT Imagem FROM Produtos WHERE ID = :id");
            $stmt->execute([':id' => $id]);
            $produto = $stmt->fetch();
            
            if ($produto && !empty($produto['Imagem'])) {
                $imagePath = '../' . $produto['Imagem'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Apagar produto
            $stmt = $db->prepare("DELETE FROM Produtos WHERE ID = :id");
            $stmt->execute([':id' => $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Produto apagado com sucesso'
            ]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            break;
    }
    
} catch (Exception $e) {
    error_log("API Produtos Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor'
    ]);
}
?>
