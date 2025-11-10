# 📝 Sistema de Textos Dinâmicos - Paulimane

## ✅ O que foi implementado

Sistema completo para gestão dinâmica do texto "Sobre Nós" da página principal através do backoffice.

### 📁 Ficheiros Criados/Modificados

```
/index.php (renomeado de index.html)   ← Página principal com PHP
/backoffice/textos.html                ← Página de gestão de textos
/backoffice/js/textos.js               ← Lógica frontend
/backoffice/api/textos/get.php         ← API para obter texto
/backoffice/api/textos/update.php      ← API para atualizar texto
/backoffice/sql/create_textos_table.sql ← Script SQL
```

## 🔧 Instalação

### **1. Criar a Tabela na Base de Dados**

Execute o SQL no phpMyAdmin ou MySQL:

```sql
CREATE TABLE IF NOT EXISTS Textos (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Chave VARCHAR(50) NOT NULL UNIQUE,
    Texto TEXT NOT NULL,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO Textos (Chave, Texto) VALUES
('sobrenos', 'A Paulimane - Ferragens Manuel Carmo & Azevedo, Lda é uma empresa portuguesa dedicada à comercialização de ferragens e tubagens de alta qualidade desde o ano 2000.

Com mais de duas décadas de experiência no mercado, especializamo-nos em fornecer soluções completas em tubagens industriais e ferragens para os mais diversos sectores, sempre com foco na excelência e satisfação dos nossos clientes.

A nossa missão é oferecer produtos de qualidade superior, aliados a um serviço personalizado e profissional, garantindo que cada cliente encontre exatamente o que precisa para os seus projetos.')
ON DUPLICATE KEY UPDATE Texto = VALUES(Texto);
```

### **2. Atualizar Links**

Todos os links para `index.html` devem ser atualizados para `index.php`:

- `login.html` → Redirecionar para `index.php`
- `js/site-login.js` → Redirecionar para `index.php`
- `js/auth-protection.js` → Redirecionar para `index.php`
- Links internos do site

### **3. Configurar Servidor**

**Local (PHP Built-in Server):**
```bash
php -S localhost:8000
```

**Produção (Apache/Nginx):**
- Certifique-se que PHP está ativo
- Módulo `mod_rewrite` ativo (se usar .htaccess)

## 🚀 Como Funciona

### **1. Página Principal (index.php)**

```php
// Carrega o texto da base de dados
$stmt = $db->prepare("SELECT Texto FROM Textos WHERE Chave = 'sobrenos'");
$stmt->execute();
$result = $stmt->fetch();

// Divide em parágrafos (separados por \n\n)
$paragrafos = explode("\n\n", $result['Texto']);

// Exibe dinamicamente
foreach ($paragrafos as $paragrafo) {
    echo "<p class='about-text'>" . nl2br(htmlspecialchars($paragrafo)) . "</p>";
}
```

### **2. Backoffice (textos.html)**

1. **Aceder:** `http://localhost:8000/backoffice/textos.html`
2. **Editar:** Escrever o texto na textarea
3. **Separar parágrafos:** Deixar **duas linhas em branco**
4. **Pré-visualizar:** Ver como ficará no site
5. **Guardar:** Clique em "Guardar Alterações"
6. **Resultado:** Texto atualizado **imediatamente** no site!

## 📋 Formato do Texto

### **Como separar parágrafos:**

```
Primeiro parágrafo aqui.

Segundo parágrafo aqui.

Terceiro parágrafo aqui.
```

**Importante:** Use **duas quebras de linha** (Enter duas vezes) para criar um novo parágrafo.

### **Exemplo:**

**Entrada no backoffice:**
```
A Paulimane é uma empresa portuguesa.

Com mais de 20 anos de experiência.

Nossa missão é oferecer qualidade.
```

**Saída no site:**
```html
<p class="about-text">A Paulimane é uma empresa portuguesa.</p>
<p class="about-text">Com mais de 20 anos de experiência.</p>
<p class="about-text">Nossa missão é oferecer qualidade.</p>
```

## 🎨 Funcionalidades do Backoffice

### **✅ Editor de Texto**
- Textarea grande e confortável
- Contador de caracteres (máx. 2000)
- Aviso quando próximo do limite

### **✅ Pré-visualização em Tempo Real**
- Veja como o texto ficará no site
- Atualiza enquanto digita
- Mostra parágrafos separados

### **✅ Validações**
- Texto obrigatório
- Máximo 2000 caracteres
- Mensagens de erro claras

### **✅ Feedback Visual**
- Mensagem de sucesso ao guardar
- Mensagem de erro se falhar
- Loading state no botão

## 🔒 Segurança

- ✅ **Autenticação obrigatória** no backoffice
- ✅ **Escape de HTML** (`htmlspecialchars`)
- ✅ **Proteção SQL Injection** (PDO prepared statements)
- ✅ **Validação de tamanho** (máx. 2000 caracteres)
- ✅ **Sanitização de entrada**

## 📊 Estrutura da Tabela

```sql
CREATE TABLE Textos (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Chave VARCHAR(50) NOT NULL UNIQUE,    -- Ex: 'sobrenos'
    Texto TEXT NOT NULL,                   -- Conteúdo do texto
    UpdatedAt TIMESTAMP                    -- Data da última atualização
);
```

### **Chaves disponíveis:**
- `sobrenos` - Texto da secção "Sobre Nós"

**Para adicionar mais textos editáveis:**
1. Adicione nova chave na tabela
2. Crie novo formulário em `textos.html`
3. Carregue o texto em `index.php`

## 🔄 Fluxo Completo

```
1. Utilizador acede ao backoffice
   ↓
2. Faz login
   ↓
3. Clica em "Textos do Site"
   ↓
4. Edita o texto do "Sobre Nós"
   ↓
5. Clica em "Guardar Alterações"
   ↓
6. API atualiza na base de dados
   ↓
7. index.php carrega o novo texto
   ↓
8. Visitantes veem o texto atualizado!
```

## 🛠️ Troubleshooting

### **Problema: Texto não aparece no site**

**Solução:**
1. Verifique se a tabela `Textos` existe
2. Verifique se há dados: `SELECT * FROM Textos WHERE Chave = 'sobrenos'`
3. Verifique logs de erro do PHP

### **Problema: Erro ao guardar**

**Solução:**
1. Verifique autenticação (faça login novamente)
2. Verifique conexão com a base de dados
3. Verifique permissões da tabela

### **Problema: Parágrafos não separam**

**Solução:**
- Use **duas linhas em branco** (Enter duas vezes)
- Não use apenas uma linha em branco

## 📝 Adicionar Mais Textos Editáveis

### **1. Adicionar na Base de Dados:**

```sql
INSERT INTO Textos (Chave, Texto) VALUES
('contacto_descricao', 'Tem alguma questão? Entre em contacto!');
```

### **2. Adicionar em index.php:**

```php
// Carregar texto
$stmt = $db->prepare("SELECT Texto FROM Textos WHERE Chave = 'contacto_descricao'");
$stmt->execute();
$contactoDesc = $stmt->fetch()['Texto'];
```

```html
<!-- Usar no HTML -->
<p><?php echo htmlspecialchars($contactoDesc); ?></p>
```

### **3. Adicionar em textos.html:**

Copie o formulário existente e adapte para a nova chave.

## ✅ Checklist de Instalação

- [ ] Tabela `Textos` criada
- [ ] Dados iniciais inseridos
- [ ] `index.html` renomeado para `index.php`
- [ ] Links atualizados para `index.php`
- [ ] Testado localmente
- [ ] Testado edição no backoffice
- [ ] Testado visualização no site
- [ ] Upload para servidor
- [ ] Testado em produção

## 🎉 Pronto!

O sistema de textos dinâmicos está **100% funcional**!

**Acesse:**
- **Site:** `http://localhost:8000/index.php`
- **Backoffice:** `http://localhost:8000/backoffice/textos.html`

**Credenciais:**
- Email: `admin@paulimane.pt`
- Password: `admin`

---

**Desenvolvido para Paulimane - Ferragens Manuel Carmo & Azevedo, Lda.**
