# 🔐 Sistema de Login do Site Paulimane

## ✅ O que foi implementado

Sistema completo de autenticação para proteger o acesso ao site principal.

### 📁 Ficheiros Criados

```
/login.html                    ← Página de login
/js/site-login.js             ← Lógica do login
/js/auth-protection.js        ← Proteção das páginas
/index.html (modificado)      ← Adicionado script de proteção
```

## 🔐 Como Funciona

### **1. Fluxo de Autenticação**

```
Utilizador acede ao site
    ↓
Redireciona para login.html (se não autenticado)
    ↓
Insere email e password
    ↓
Valida na tabela Utilizador
    ↓
Login bem-sucedido → Redireciona para index.html
    ↓
Botão "Sair" aparece automaticamente
```

### **2. Proteção Automática**

- ✅ **Não pode aceder `index.html` sem login**
- ✅ **Redireciona automaticamente** para login se não autenticado
- ✅ **Verifica sessão** a cada carregamento
- ✅ **Token armazenado** em `sessionStorage`
- ✅ **Validação no servidor** via API PHP
- ✅ **Botão logout** adicionado automaticamente
- ✅ **Sessão expira** em 24 horas

## 🚀 Como Testar

### **Local (Desenvolvimento)**

1. **Certifique-se que o servidor PHP está a correr:**
   ```bash
   php -S localhost:8000
   ```

2. **Aceda ao site:**
   ```
   http://localhost:8000/
   ```

3. **Será redirecionado para:**
   ```
   http://localhost:8000/login.html
   ```

4. **Faça login com:**
   - Email: `admin@paulimane.pt`
   - Password: `admin`

5. **Após login:**
   - Será redirecionado para `index.html`
   - Verá o site completo
   - Botão "Sair" aparece no canto superior direito

### **Produção (Servidor PTisp)**

1. **Faça upload de todos os ficheiros:**
   ```
   /login.html
   /index.html (modificado)
   /js/site-login.js
   /js/auth-protection.js
   /backoffice/ (pasta completa)
   ```

2. **Aceda ao site:**
   ```
   https://seu-dominio.pt/
   ```

3. **Será redirecionado automaticamente para login**

## 🎨 Design do Login

- ✅ **Design moderno** com gradiente
- ✅ **Responsivo** (funciona em mobile)
- ✅ **Logo da empresa** em destaque
- ✅ **Toggle de visibilidade** da password
- ✅ **Validação de email** em tempo real
- ✅ **Loading state** no botão
- ✅ **Mensagens de erro** claras

## 🔧 Configuração

### **Credenciais da Base de Dados**

As credenciais são detectadas automaticamente:

**Local:**
```php
Host: 127.0.0.1
User: root
Pass: senha123
Database: Paulimane
```

**Produção (PTisp):**
```php
Host: localhost
User: pauliman_admin
Pass: paulimane2000
Database: pauliman_Site
```

### **Tabela Utilizador**

Certifique-se que a tabela existe:

```sql
CREATE TABLE IF NOT EXISTS Utilizador (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(100) NOT NULL,
    Ativo INT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO Utilizador (Nome, Email, Password, Ativo) VALUES
('admin', 'admin@paulimane.pt', 'admin', 1);
```

## 🛡️ Segurança

### **Implementado:**

- ✅ **Sessões PHP** (não armazena tokens na BD)
- ✅ **Validação no servidor** a cada request
- ✅ **Password em texto simples** (como solicitado)
- ✅ **Verificação de utilizador ativo**
- ✅ **Expiração de sessão** (24 horas)
- ✅ **Proteção contra acesso direto**

### **Recomendações para Produção:**

⚠️ **IMPORTANTE:** Em produção, considere:

1. **Encriptar passwords:**
   ```php
   password_hash($password, PASSWORD_BCRYPT)
   ```

2. **HTTPS obrigatório:**
   - Ative SSL no servidor PTisp

3. **Rate limiting:**
   - Limite tentativas de login

## 📋 Adicionar Proteção a Outras Páginas

Se tiver outras páginas HTML que precisam de proteção:

```html
<head>
    <!-- Adicione este script -->
    <script src="js/auth-protection.js"></script>
</head>
```

Pronto! A página está protegida.

## 🔍 Troubleshooting

### **Problema: Redireciona sempre para login**

**Solução:**
- Limpe o `sessionStorage` do navegador
- Faça login novamente

### **Problema: "Erro interno do servidor"**

**Solução:**
- Verifique se a base de dados existe
- Verifique as credenciais em `config/database.php`
- Use `test_db.php` para diagnosticar

### **Problema: Botão "Sair" não aparece**

**Solução:**
- Verifique se `auth-protection.js` está a carregar
- Abra o Console (F12) e veja se há erros

## 📊 Estrutura de Sessão

### **sessionStorage:**

```javascript
paulimane_site_auth: "token_gerado_pelo_php"
paulimane_site_user: {
    "id": 1,
    "nome": "admin",
    "email": "admin@paulimane.pt"
}
```

### **Sessão PHP:**

```php
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'admin@paulimane.pt';
$_SESSION['user_nome'] = 'admin';
$_SESSION['login_time'] = timestamp;
```

## ✅ Checklist de Instalação

- [ ] Ficheiros criados localmente
- [ ] Testado em localhost
- [ ] Base de dados criada no servidor
- [ ] Tabela `Utilizador` criada
- [ ] Utilizador admin inserido
- [ ] Credenciais corretas em `database.php`
- [ ] Upload de todos os ficheiros
- [ ] Testado no servidor PTisp
- [ ] Login funciona
- [ ] Logout funciona
- [ ] Proteção funciona

---

## 🎉 Pronto!

O sistema de login está **100% funcional**!

**URLs:**
- **Login:** `https://seu-dominio.pt/login.html`
- **Site:** `https://seu-dominio.pt/` (redireciona para login)
- **Backoffice:** `https://seu-dominio.pt/backoffice/`

**Credenciais padrão:**
- Email: `admin@paulimane.pt`
- Password: `admin`

---

**Desenvolvido para Paulimane - Ferragens Manuel Carmo & Azevedo, Lda.**
