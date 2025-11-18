<?php
/**
 * Proteção de Acesso - Nível 1 ou Superior
 */
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Todos os níveis autenticados podem acessar textos
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós | Paulimane Backoffice</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/logooriginal.png?v=2" type="image/png">
    <style>
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .content-card h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card h3 svg {
            color: #F26522;
        }

        .content-card .description {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.6;
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

        .form-group input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #F26522;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            resize: vertical;
            min-height: 200px;
            transition: all 0.3s ease;
            line-height: 1.6;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #F26522;
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .helper-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }

        .btn-save {
            background: linear-gradient(135deg, #F26522 0%, #D95518 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(242, 101, 34, 0.3);
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .preview-box {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .preview-box h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .preview-content {
            color: #333;
            line-height: 1.8;
        }

        .preview-content p {
            margin-bottom: 15px;
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

            <div class="page-header" style="margin-bottom: 30px;">
                <h1>Gestão do Sobre Nós</h1>
                <p>Editar textos e estatísticas da secção "Sobre Nós"</p>
            </div>

            <!-- Sobre Nós -->
            <div class="content-card">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    Sobre Nós
                </h3>
                <p class="description">
                    Este texto aparece na secção "Sobre Nós" da página principal. 
                    <strong>Separe parágrafos com duas linhas em branco</strong> para criar múltiplos parágrafos.
                </p>
                
                <form id="sobrenosForm">
                    <div class="form-group">
                        <label for="sobrenosTexto">Texto *</label>
                        <textarea id="sobrenosTexto" name="texto" required maxlength="2000" placeholder="Escreva o texto do Sobre Nós aqui...&#10;&#10;Deixe duas linhas em branco para criar um novo parágrafo."></textarea>
                        <div class="char-count" id="charCount">0 / 2000 caracteres</div>
                        <div class="helper-text">💡 Dica: Use duas quebras de linha para separar parágrafos</div>
                    </div>

                    <div class="preview-box">
                        <h4>📄 Pré-visualização:</h4>
                        <div class="preview-content" id="preview">
                            <p><em>O texto aparecerá aqui...</em></p>
                        </div>
                    </div>

                    <button type="submit" class="btn-save" id="btnSave" style="margin-top: 20px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Guardar Alterações
                    </button>
                </form>
            </div>

            <!-- Estatísticas -->
            <div class="content-card">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 20V10"></path>
                        <path d="M12 20V4"></path>
                        <path d="M6 20v-6"></path>
                    </svg>
                    Estatísticas
                </h3>
                <p class="description">
                    Edite os números e textos das estatísticas que aparecem abaixo do texto "Sobre Nós".
                </p>
                
                <form id="estatisticasForm">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
                        <!-- Estatística 1 -->
                        <div>
                            <div class="form-group">
                                <label for="numero1">Número 1 *</label>
                                <input type="text" id="numero1" name="numero1" required maxlength="10" placeholder="23+">
                                <div class="helper-text">Ex: 23+, 500+, 100%</div>
                            </div>
                            <div class="form-group">
                                <label for="numero_texto1">Texto 1 *</label>
                                <input type="text" id="numero_texto1" name="numero_texto1" required maxlength="50" placeholder="Anos de Experiência">
                            </div>
                        </div>

                        <!-- Estatística 2 -->
                        <div>
                            <div class="form-group">
                                <label for="numero2">Número 2 *</label>
                                <input type="text" id="numero2" name="numero2" required maxlength="10" placeholder="500+">
                                <div class="helper-text">Ex: 23+, 500+, 100%</div>
                            </div>
                            <div class="form-group">
                                <label for="numero_texto2">Texto 2 *</label>
                                <input type="text" id="numero_texto2" name="numero_texto2" required maxlength="50" placeholder="Clientes Satisfeitos">
                            </div>
                        </div>

                        <!-- Estatística 3 -->
                        <div>
                            <div class="form-group">
                                <label for="numero3">Número 3 *</label>
                                <input type="text" id="numero3" name="numero3" required maxlength="10" placeholder="100%">
                                <div class="helper-text">Ex: 23+, 500+, 100%</div>
                            </div>
                            <div class="form-group">
                                <label for="numero_texto3">Texto 3 *</label>
                                <input type="text" id="numero_texto3" name="numero_texto3" required maxlength="50" placeholder="Qualidade Garantida">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-save" id="btnSaveStats">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Guardar Estatísticas
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="js/dashboard.js"></script>
    <script src="js/textos.js"></script>
</body>
</html>
