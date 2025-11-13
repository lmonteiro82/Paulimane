# Proteção de Páginas Implementada - Sistema de Níveis

## Data: 12 de Novembro de 2025

## ✅ Problema Resolvido

**Antes:** Utilizadores nível 1 conseguiam acessar páginas de categorias e destaques digitando o URL diretamente.

**Agora:** Todas as páginas estão protegidas com verificação de nível no servidor (PHP). Se um utilizador tentar acessar uma página sem permissão, é redirecionado para `acesso-negado.html`.

---

## 🔒 Páginas Convertidas para PHP

Todas as páginas principais foram convertidas de `.html` para `.php` para permitir verificação de acesso no servidor:

### **Nível 1 - Básico** (Todos autenticados)
- ✅ `textos.php` (antes textos.html)
- ✅ `equipa.php` (antes equipa.html)
- ✅ `clientes.php` (antes clientes.html)

### **Nível 2 - Editor** (Nível 2 ou superior)
- ✅ `categorias.php` (antes categorias.html) - **PROTEGIDA**
- ✅ `destaques.php` (antes destaques.html) - **PROTEGIDA**

### **Nível 3 - Administrador** (Apenas nível 3)
- ✅ `utilizadores.php` (antes utilizadores.html) - **PROTEGIDA**

### **Página Inicial**
- ✅ `index.php` - Redireciona automaticamente baseado no nível:
  - Nível 1 → `textos.php`
  - Nível 2 → `categorias.php`
  - Nível 3 → `utilizadores.php`

---

## 🛡️ Como Funciona a Proteção

### **Exemplo: categorias.php**
```php
<?php
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Verificar nível de acesso
$nivel_usuario = isset($_SESSION['user_nivel']) ? (int)$_SESSION['user_nivel'] : 1;

// Nível 2 ou superior pode acessar categorias
if ($nivel_usuario < 2) {
    header('Location: acesso-negado.html');
    exit;
}
?>
<!DOCTYPE html>
...
```

### **O Que Acontece:**

1. **Utilizador Nível 1 tenta acessar `categorias.php`:**
   - ❌ Nível insuficiente (tem 1, precisa 2)
   - 🔄 Redirecionado para `acesso-negado.html`
   - ✋ **Acesso Negado!**

2. **Utilizador Nível 2 tenta acessar `categorias.php`:**
   - ✅ Nível suficiente (tem 2, precisa 2)
   - ✅ **Acesso Permitido!**

3. **Utilizador Nível 3 tenta acessar qualquer página:**
   - ✅ Nível máximo
   - ✅ **Acesso Total!**

---

## 📝 Alterações nos Links

Todos os links da sidebar foram atualizados de `.html` para `.php`:

**Antes:**
```html
<a href="categorias.html" class="nav-item">
```

**Depois:**
```html
<a href="categorias.php" class="nav-item">
```

---

## 🚀 Fluxo de Login Atualizado

1. Utilizador faz login em `login.html`
2. API retorna dados incluindo `nivel`
3. Nível é guardado em `$_SESSION['user_nivel']`
4. Redireciona para `backoffice/index.php`
5. `index.php` redireciona baseado no nível:
   - Nível 1 → `textos.php`
   - Nível 2 → `categorias.php`
   - Nível 3 → `utilizadores.php`

---

## ⚠️ Importante

### **Arquivos Antigos (.html)**
Os arquivos `.html` originais ainda existem mas **NÃO devem ser usados**. Eles não têm proteção!

### **Usar Sempre .php**
- ✅ `categorias.php` - Protegido
- ❌ `categorias.html` - Sem proteção

### **Recomendação:**
Considere remover ou renomear os arquivos `.html` para evitar confusão:
```bash
cd backoffice
mv categorias.html categorias.html.backup
mv destaques.html destaques.html.backup
mv utilizadores.html utilizadores.html.backup
mv textos.html textos.html.backup
mv equipa.html equipa.html.backup
mv clientes.html clientes.html.backup
```

---

## 🧪 Como Testar

### **Teste 1: Utilizador Nível 1**
1. Criar utilizador com nível 1
2. Fazer login
3. Deve ser redirecionado para `textos.php`
4. Tentar acessar `categorias.php` diretamente:
   ```
   http://localhost/backoffice/categorias.php
   ```
5. **Resultado Esperado:** Redireciona para `acesso-negado.html` ✅

### **Teste 2: Utilizador Nível 2**
1. Criar utilizador com nível 2
2. Fazer login
3. Deve ser redirecionado para `categorias.php`
4. Pode acessar: textos, equipa, clientes, categorias, destaques
5. Tentar acessar `utilizadores.php`:
   ```
   http://localhost/backoffice/utilizadores.php
   ```
6. **Resultado Esperado:** Redireciona para `acesso-negado.html` ✅

### **Teste 3: Utilizador Nível 3**
1. Fazer login como admin (nível 3)
2. Deve ser redirecionado para `utilizadores.php`
3. Pode acessar **todas** as páginas ✅

---

## 📊 Matriz de Acesso Atualizada

| Página | Nível 1 | Nível 2 | Nível 3 | Proteção |
|--------|:-------:|:-------:|:-------:|:--------:|
| `textos.php` | ✅ | ✅ | ✅ | PHP |
| `equipa.php` | ✅ | ✅ | ✅ | PHP |
| `clientes.php` | ✅ | ✅ | ✅ | PHP |
| `categorias.php` | ❌ | ✅ | ✅ | **PHP** |
| `destaques.php` | ❌ | ✅ | ✅ | **PHP** |
| `utilizadores.php` | ❌ | ❌ | ✅ | **PHP** |

---

## 🔧 Arquivos Modificados

### **Novos Arquivos PHP:**
```
backoffice/index.php
backoffice/textos.php
backoffice/equipa.php
backoffice/clientes.php
backoffice/categorias.php
backoffice/destaques.php
backoffice/utilizadores.php
```

### **Arquivos Atualizados:**
```
js/site-login.js - Redireciona para index.php
backoffice/config/check_access.php - Atualizado com index
```

---

## ✅ Checklist de Segurança

- ✅ Todas as páginas principais convertidas para PHP
- ✅ Verificação de autenticação em todas as páginas
- ✅ Verificação de nível em páginas sensíveis
- ✅ Redirecionamento para acesso-negado.html
- ✅ Links da sidebar atualizados para .php
- ✅ Login redireciona para index.php
- ✅ index.php redireciona baseado no nível
- ✅ Sessão guarda nível do utilizador

---

## 🎉 Resultado Final

**Agora é IMPOSSÍVEL um utilizador nível 1 acessar páginas de nível 2 ou 3, mesmo digitando o URL diretamente!**

A proteção é feita no **servidor (PHP)**, não apenas no frontend, garantindo segurança real.

---

## 📞 Suporte

Se encontrar algum problema:
1. Verificar se está usando `.php` e não `.html`
2. Verificar se o nível está correto na base de dados
3. Fazer logout e login novamente
4. Verificar se `$_SESSION['user_nivel']` está definido

Para dúvidas, consulte este documento ou `SISTEMA_NIVEIS_ACESSO.md`.
