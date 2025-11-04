-- ============================================
-- Script de Criação de Base de Dados MySQL
-- Base de Dados: paulimane_db
-- ============================================

-- Criar a base de dados se não existir
CREATE DATABASE IF NOT EXISTS paulimane_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Usar a base de dados
USE paulimane_db;

-- ============================================
-- Tabela: utilizadores
-- ============================================

CREATE TABLE IF NOT EXISTS utilizadores (
    -- Chave primária
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Informações de autenticação
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    
    -- Informações pessoais
    nome VARCHAR(100) NOT NULL,
    apelido VARCHAR(100) NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(20),
    
    -- Endereço
    morada VARCHAR(255),
    cidade VARCHAR(100),
    codigo_postal VARCHAR(10),
    pais VARCHAR(50) DEFAULT 'Portugal',
    
    -- Status da conta
    ativo BOOLEAN DEFAULT TRUE,
    email_verificado BOOLEAN DEFAULT FALSE,
    
    -- Perfil e permissões
    tipo_utilizador ENUM('admin', 'utilizador', 'moderador') DEFAULT 'utilizador',
    
    -- Timestamps
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP NULL,
    
    -- Índices para melhorar performance
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_ativo (ativo),
    INDEX idx_tipo_utilizador (tipo_utilizador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Inserir utilizador administrador padrão
-- ============================================
-- Nota: A password 'admin123' está em hash MD5 apenas para exemplo
-- Em produção, use bcrypt ou argon2

INSERT INTO utilizadores (
    username, 
    email, 
    password_hash, 
    nome, 
    apelido, 
    tipo_utilizador,
    email_verificado
) VALUES (
    'admin',
    'admin@paulimane.com',
    MD5('admin123'),
    'Administrador',
    'Sistema',
    'admin',
    TRUE
);

-- ============================================
-- Verificar a criação
-- ============================================

SELECT 'Base de dados e tabela criadas com sucesso!' AS status;
DESCRIBE utilizadores;
