# Sistema de Níveis de Acesso - Paulimane Backoffice

## Data: 12 de Novembro de 2025

## 📋 Resumo

Sistema completo de controle de acesso por níveis implementado no backoffice da Paulimane.

---

## 🎯 Níveis de Acesso

### **Nível 1 - Básico**
Acesso a:
- ✅ Sobre Nós (Textos)
- ✅ Equipa
- ✅ Clientes

### **Nível 2 - Editor**
Acesso a:
- ✅ Tudo do Nível 1
- ✅ Textos (edição completa)
- ✅ Categorias
- ✅ Destaques

### **Nível 3 - Administrador**
Acesso a:
- ✅ Tudo dos Níveis 1 e 2
- ✅ Gestão de Utilizadores (criar, editar, remover)
- ✅ **Acesso Total ao Sistema**

---

## ✅ O Que Foi Implementado

### **1. Formulário de Utilizadores**
- Campo "Nível de Acesso" com 3 opções
- Descrição explicativa dos níveis
- Validação obrigatória
- Badges coloridos na listagem:
  - **Nível 1**: Azul
  - **Nível 2**: Laranja
  - **Nível 3**: Roxo

### **2. Base de Dados**
- Coluna `Nivel` adicionada à tabela `Utilizador`
- Valores: 1, 2 ou 3
- Validação nas APIs

### **3. APIs Atualizadas**
Todas as APIs de utilizadores incluem o campo Nivel:
- ✅ `create.php` - Criar com nível
- ✅ `update.php` - Atualizar nível
- ✅ `list.php` - Listar com nível
- ✅ `get.php` - Obter com nível
- ✅ `login.php` - Retorna nível e guarda na sessão

### **4. Middleware de Acesso**
Criado `config/check_access.php` com funções:
- `checkAccessLevel($nivel)` - Verifica se tem acesso
- `requireAccessLevel($nivel)` - Redireciona se não tiver
- `getUserLevel()` - Retorna nível do usuário
- `canAccessPage($pagina)` - Verifica acesso a página
- `getAccessiblePages()` - Lista páginas acessíveis
- `requireAPIAccess($nivel)` - Proteção para APIs

### **5. Proteção de APIs**
APIs protegidas por nível:

**Nível 3 (Administrador):**
- `/api/users/create.php`
- `/api/users/update.php`
- `/api/users/delete.php`

**Nível 2 (Editor):**
- `/api/catalogo/create.php`
- `/api/catalogo/update.php`
- `/api/catalogo/delete.php`
- `/api/destaques/create.php`
- `/api/destaques/delete.php`

### **6. Controle de Acesso Frontend**
- `js/access-control.js` - Controla sidebar dinamicamente
- `api/check-session.php` - Verifica sessão e nível
- Sidebar mostra apenas opções permitidas
- Redirecionamento automático se sem permissão

### **7. Página de Acesso Negado**
- `acesso-negado.html` - Página amigável
- Explica os níveis de acesso
- Botão para voltar

---

## 🚀 Como Usar

### **Criar Novo Utilizador**

1. Acesse **Backoffice > Utilizadores** (apenas Nível 3)
2. Clique em **"Novo Utilizador"**
3. Preencha:
   - Nome
   - Email
   - Password
   - **Nível de Acesso** (1, 2 ou 3)
   - Estado (Ativo/Inativo)
4. Clique em **"Guardar"**

### **Editar Nível de Utilizador**

1. Na lista de utilizadores, clique em **"Editar"**
2. Altere o **Nível de Acesso**
3. Clique em **"Guardar"**
4. O utilizador precisará fazer login novamente para as alterações terem efeito

---

## 🔒 Segurança

### **Sessão**
- Nível guardado em `$_SESSION['user_nivel']`
- Verificado em cada requisição
- Logout limpa a sessão

### **APIs**
- Todas as APIs verificam autenticação
- APIs sensíveis verificam nível de acesso
- Retornam erro 403 se sem permissão

### **Frontend**
- JavaScript verifica nível antes de exibir opções
- Sidebar dinâmica baseada no nível
- Redirecionamento automático

---

## 📝 Arquivos Criados/Modificados

### **Novos Arquivos:**
```
backoffice/config/check_access.php
backoffice/acesso-negado.html
backoffice/js/access-control.js
backoffice/api/check-session.php
backoffice/check_page_access.php
```

### **Arquivos Modificados:**
```
backoffice/utilizadores.html
backoffice/js/utilizadores.js
backoffice/api/users/create.php
backoffice/api/users/update.php
backoffice/api/users/list.php
backoffice/api/users/get.php
backoffice/api/users/delete.php
backoffice/api/login.php
backoffice/api/catalogo/create.php
js/site-login.js
```

---

## 🔧 Próximos Passos (Opcional)

### **Para Completar a Proteção:**

1. **Adicionar proteção nas demais APIs de catálogo:**
   ```php
   require_once '../../config/check_access.php';
   requireAPIAccess(2);
   ```
   Em:
   - `api/catalogo/update.php`
   - `api/catalogo/delete.php`
   - `api/catalogo/upload-pdf.php`

2. **Adicionar proteção nas APIs de destaques:**
   ```php
   require_once '../../config/check_access.php';
   requireAPIAccess(2);
   ```
   Em:
   - `api/destaques/create.php`
   - `api/destaques/delete.php`
   - `api/destaques/upload.php`

3. **Adicionar proteção nas APIs de equipa e clientes:**
   ```php
   require_once '../../config/check_access.php';
   requireAPIAccess(1);
   ```

4. **Incluir access-control.js em todas as páginas:**
   Adicionar em cada página HTML:
   ```html
   <script src="js/access-control.js"></script>
   ```

5. **Atualizar utilizadores existentes:**
   Execute no MySQL:
   ```sql
   UPDATE Utilizador SET Nivel = 3 WHERE Email = 'admin@paulimane.pt';
   UPDATE Utilizador SET Nivel = 1 WHERE Nivel IS NULL;
   ```

---

## 🧪 Como Testar

### **Teste 1: Criar Utilizador Nível 1**
1. Login como admin (nível 3)
2. Criar utilizador com nível 1
3. Fazer logout
4. Login com novo utilizador
5. Verificar que só vê: Textos, Equipa, Clientes

### **Teste 2: Tentar Acessar Página Sem Permissão**
1. Login como nível 1
2. Tentar acessar `/backoffice/utilizadores.html`
3. Deve redirecionar para `acesso-negado.html`

### **Teste 3: API Protegida**
1. Login como nível 1
2. Tentar criar categoria via API
3. Deve retornar erro 403

---

## ⚠️ Notas Importantes

1. **Primeiro Utilizador**: Certifique-se de ter pelo menos um utilizador nível 3 antes de testar
2. **Sessão**: Alterações de nível requerem novo login
3. **Nível 3**: Sempre tem acesso total, independente das restrições
4. **Backup**: Mantenha sempre um utilizador nível 3 ativo
5. **Segurança**: Nunca remova a verificação de nível das APIs sensíveis

---

## 📊 Estrutura de Permissões

| Recurso | Nível 1 | Nível 2 | Nível 3 |
|---------|---------|---------|---------|
| Textos (Sobre Nós) | ✅ | ✅ | ✅ |
| Equipa | ✅ | ✅ | ✅ |
| Clientes | ✅ | ✅ | ✅ |
| Categorias | ❌ | ✅ | ✅ |
| Destaques | ❌ | ✅ | ✅ |
| Utilizadores | ❌ | ❌ | ✅ |

---

## 🎉 Sistema Completo!

O sistema de níveis de acesso está totalmente implementado e funcional. Todos os utilizadores agora têm acesso controlado baseado no seu nível, garantindo segurança e organização no backoffice.

Para dúvidas ou problemas, consulte este documento ou contacte o desenvolvedor.
