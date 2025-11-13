# Remoção da Secção de Produtos

## Data: 12 de Novembro de 2025

## Resumo

A secção de **Produtos** foi completamente removida do backoffice. Agora o sistema funciona apenas com **Categorias** que contêm PDFs.

---

## Alterações Implementadas

### 1. **Site Principal**
- `js/catalog.js` - Modificado para **sempre abrir o PDF** ao clicar numa categoria
- Removido o fallback para `produtos.html`
- Todas as categorias agora abrem o PDF em nova aba

### 2. **Backoffice - Arquivos Removidos/Renomeados**

Os seguintes arquivos foram renomeados como backup (podem ser deletados):

```
backoffice/produtos.html → backoffice/produtos_old.html
backoffice/js/produtos.js → backoffice/js/produtos_old.js
backoffice/api/produtos/ → backoffice/api/produtos_old/
```

### 3. **Backoffice - Sidebar Atualizada**

O link para "Produtos" foi removido da sidebar de todos os arquivos:
- ✅ `categorias.html`
- ✅ `destaques.html`
- ✅ `clientes.html`
- ✅ `equipa.html`
- ✅ `textos.html`
- ✅ `utilizadores.html`

---

## Nova Estrutura do Menu Backoffice

```
📋 Utilizadores
📄 Sobre Nós
👥 Equipa
🏢 Clientes
📺 Categorias  ← Agora com upload de PDF
⭐ Destaques   ← Agora independentes
```

---

## Como Funciona Agora

### Categorias:
1. Acesse **Backoffice > Categorias**
2. Ao criar/editar uma categoria, você deve:
   - Fazer upload de uma **imagem**
   - Inserir **nome** e **descrição**
   - Fazer upload de um **PDF** (obrigatório)
3. No site principal, ao clicar na categoria, **abre o PDF em nova aba**

### Destaques:
1. Acesse **Backoffice > Destaques**
2. Crie destaques independentes com:
   - **Imagem**
   - **Nome**
   - **Descrição**
3. Máximo de 6 destaques

---

## Notas Importantes

### ⚠️ Categorias Existentes
- Categorias criadas antes desta alteração podem não ter PDF
- Você precisará editar cada categoria e adicionar um PDF
- Sem PDF, o link não funcionará (irá para "#")

### 🗑️ Limpeza (Opcional)
Após confirmar que tudo está funcionando, você pode deletar:
```bash
rm backoffice/produtos_old.html
rm backoffice/js/produtos_old.js
rm -rf backoffice/api/produtos_old/
```

### 📊 Tabela Produtos
A tabela `Produtos` na base de dados **não foi removida**. Se desejar removê-la:
```sql
-- CUIDADO: Isso remove permanentemente todos os produtos
DROP TABLE IF EXISTS Produtos;
```

---

## Fluxo Completo do Sistema

```
SITE PRINCIPAL (catalogo.html)
    ↓
Carrega categorias via API (api/catalogo.php)
    ↓
Exibe cards de categorias
    ↓
Usuário clica numa categoria
    ↓
Abre o PDF em nova aba
```

```
BACKOFFICE
    ↓
Categorias: Cria categoria com PDF
    ↓
Destaques: Cria destaques independentes
    ↓
Ambos aparecem no site principal
```

---

## Troubleshooting

### Categoria não abre nada ao clicar:
- Verifique se o campo PDF foi preenchido na base de dados
- Verifique se o arquivo PDF existe no servidor

### Link vai para "#":
- A categoria não tem PDF associado
- Edite a categoria e adicione um PDF

---

## Resumo das Mudanças

| Antes | Depois |
|-------|--------|
| Categorias → Produtos → Detalhes | Categorias → PDF |
| Destaques vinculados a produtos | Destaques independentes |
| 3 níveis de navegação | 1 nível de navegação |
| Gestão de produtos no backoffice | Sem gestão de produtos |

---

Todas as alterações foram implementadas com sucesso! ✅
