# Correção: Login do Site Principal

## Problema Identificado
O login do site principal (`login.html`) estava redirecionando para `backoffice/index.php` em vez de `index.php` (site principal).

## Solução Aplicada

### Arquivo Corrigido: `js/site-login.js`

**Antes:**
```javascript
setTimeout(() => {
    window.location.href = 'backoffice/index.php';
}, 1000);
```

**Depois:**
```javascript
setTimeout(() => {
    window.location.href = 'index.php';
}, 1000);
```

## Fluxo Correto Agora

### **Site Principal (Público)**
1. Acessa `login.html` (site principal)
2. Faz login
3. Redireciona para `index.php` (site principal) ✅

### **Backoffice (Administrativo)**
1. Acessa `backoffice/login.html`
2. Faz login
3. Redireciona para `backoffice/index.php`
4. `index.php` redireciona baseado no nível:
   - Nível 1 → `textos.php`
   - Nível 2 → `categorias.php`
   - Nível 3 → `utilizadores.php`

## Separação de Logins

### **Login do Site Principal**
- **URL:** `/login.html`
- **Script:** `js/site-login.js`
- **API:** `backoffice/api/login.php`
- **Redireciona para:** `index.php` (site principal)
- **Sem proteção de níveis**

### **Login do Backoffice**
- **URL:** `/backoffice/login.html`
- **Script:** (próprio do backoffice)
- **API:** `api/login.php`
- **Redireciona para:** `index.php` (backoffice)
- **Com proteção de níveis** ✅

## Sistema de Níveis

**IMPORTANTE:** O sistema de níveis de acesso **APENAS** se aplica ao backoffice!

### **Site Principal (index.php)**
- ✅ Sem verificação de níveis
- ✅ Apenas verifica autenticação
- ✅ Todos os utilizadores autenticados podem acessar

### **Backoffice (backoffice/*.php)**
- ✅ Verifica autenticação
- ✅ Verifica nível de acesso
- ✅ Redireciona se sem permissão

## Teste

1. **Acesse:** `http://localhost/login.html`
2. **Faça login**
3. **Resultado:** Deve ir para `index.php` (site principal) ✅
4. **Não deve:** Ir para o backoffice ❌

## Conclusão

✅ Login do site principal corrigido
✅ Redireciona para o site, não para o backoffice
✅ Sistema de níveis apenas no backoffice
✅ Site principal acessível por todos os utilizadores autenticados
