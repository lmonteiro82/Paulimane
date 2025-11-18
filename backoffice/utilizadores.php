<?php
/**
 * Proteção de Acesso - Nível 3 Necessário (Administrador)
 */
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Verificar nível de acesso
$nivel_usuario = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Apenas nível 3 pode acessar gestão de utilizadores
if ($nivel_usuario < 3) {
    header('Location: acesso-negado.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Utilizadores | Paulimane Backoffice</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/logooriginal.png?v=2" type="image/png">
    <style>
        .users-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(242, 101, 34, 0.3);
        }

        .users-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
            color: #666;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .nivel-basico {
            background: #e3f2fd;
            color: #1565c0;
        }

        .nivel-editor {
            background: #fff3e0;
            color: #e65100;
        }

        .nivel-admin {
            background: #f3e5f5;
            color: #6a1b9a;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            background: #f8f9fa;
        }

        .btn-icon.edit {
            color: #0066cc;
        }

        .btn-icon.delete {
            color: #dc3545;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            font-size: 24px;
            color: #333;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .btn-close:hover {
            background: #f8f9fa;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #F26522;
        }

        .image-upload-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border: 2px dashed #e9ecef;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8f9fa;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #999;
            text-align: center;
            padding: 20px;
        }

        .image-placeholder svg {
            opacity: 0.5;
        }

        .image-placeholder span {
            font-size: 12px;
        }

        .btn-upload, .btn-remove {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .btn-upload {
            background: #F26522;
            color: white;
        }

        .btn-upload:hover {
            background: #D95518;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
        }

        .btn-remove:hover {
            background: #c82333;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .alert.show {
            display: flex;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state svg {
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <button class="menu-toggle" id="menuToggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            
            <div class="top-bar-right">
                <div class="user-info">
                    <span class="user-name" id="userName">Administrador</span>
                    <div class="user-avatar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="alert alert-success" id="successAlert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span id="successMessage"></span>
            </div>

            <div class="alert alert-error" id="errorAlert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span id="errorMessage"></span>
            </div>

            <div class="users-header">
                <div>
                    <h1>Gestão de Utilizadores</h1>
                    <p>Gerir utilizadores do sistema</p>
                </div>
                <button class="btn-primary" id="btnNewUser">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Novo Utilizador
                </button>
            </div>

            <div class="users-table">
                <div id="loadingState" class="loading">
                    <p>A carregar utilizadores...</p>
                </div>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h3>Nenhum utilizador encontrado</h3>
                    <p>Clique em "Novo Utilizador" para adicionar o primeiro</p>
                </div>

                <table id="usersTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Nível</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Novo/Editar Utilizador -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Novo Utilizador</h2>
                <button class="btn-close" id="btnCloseModal">&times;</button>
            </div>

            <form id="userForm">
                <input type="hidden" id="userId">
                <input type="hidden" id="userImagePath">
                
                <div class="form-group">
                    <label for="userImage">Foto de Perfil</label>
                    <div class="image-upload-container">
                        <div class="image-preview" id="userImagePreview">
                            <img id="userImagePreviewImg" src="" alt="Preview" style="display: none;">
                            <div class="image-placeholder" id="userImagePlaceholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 8v8m-4-4h8"></path>
                                </svg>
                                <span>Adicionar foto</span>
                            </div>
                        </div>
                        <input type="file" id="userImage" name="imagem" accept="image/*" style="display: none;">
                        <button type="button" class="btn-upload" id="btnSelectImage">Escolher Imagem</button>
                        <button type="button" class="btn-remove" id="btnRemoveImage" style="display: none;">Remover</button>
                        <small style="color: #999; font-size: 12px; margin-top: 8px; display: block;">
                            Formatos aceites: JPG, PNG, GIF, WEBP (máx. 5MB)
                        </small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="userName">Nome *</label>
                    <input type="text" id="userNameInput" name="nome" required placeholder="Nome completo">
                </div>

                <div class="form-group">
                    <label for="userEmail">Email *</label>
                    <input type="email" id="userEmail" name="email" required placeholder="email@exemplo.com">
                </div>

                <div class="form-group">
                    <label for="userPassword">Password *</label>
                    <input type="password" id="userPassword" name="password" placeholder="Mínimo 6 caracteres">
                    <small style="color: #999; font-size: 12px;" id="passwordHelp">Deixe em branco para manter a password atual (apenas edição)</small>
                </div>

                <div class="form-group">
                    <label for="userNivel">Nível de Acesso *</label>
                    <select id="userNivel" name="nivel" required>
                        <option value="">Selecione o nível</option>
                        <option value="1">Nível 1 - Básico (Sobre Nós, Equipa, Clientes)</option>
                        <option value="2">Nível 2 - Editor (Textos, Categorias, Destaques)</option>
                        <option value="3">Nível 3 - Administrador (Acesso Total)</option>
                    </select>
                    <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">
                        <strong>Nível 1:</strong> Acesso a Sobre Nós, Equipa e Clientes<br>
                        <strong>Nível 2:</strong> Nível 1 + Textos, Categorias e Destaques<br>
                        <strong>Nível 3:</strong> Acesso total incluindo gestão de utilizadores
                    </small>
                </div>

                <div class="form-group">
                    <label for="userStatus">Estado *</label>
                    <select id="userStatus" name="ativo" required>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btnCancelModal">Cancelar</button>
                    <button type="submit" class="btn-primary" id="btnSaveUser">
                        <span>Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="js/utilizadores.js"></script>
</body>
</html>
