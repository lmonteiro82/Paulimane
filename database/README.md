# Scripts de Base de Dados - Paulimane

## 📁 Ficheiros Disponíveis

### `sample_data.sql` (Completo)
Script completo com:
- 10 Categorias de produtos
- 60 Produtos distribuídos pelas categorias
- Queries de verificação no final
- Comentários detalhados

### `sample_data_simple.sql` (Simplificado)
Script apenas com os INSERTs, sem queries de verificação.

## 🗂️ Categorias Incluídas

1. **Calhas e Algerozes** (5 produtos)
   - Calhas PVC, alumínio, tubos de queda, suportes

2. **Torneiras e Misturadoras** (6 produtos)
   - Torneiras monocomando, misturadoras, colunas de duche

3. **Tubos e Conexões** (7 produtos)
   - Tubos PVC, cobre, multicamada, cotovelos, tês

4. **Portas e Janelas** (5 produtos)
   - Portas interiores, exteriores, janelas PVC e alumínio

5. **Sanitários** (6 produtos)
   - Sanitas, lavatórios, bidés, bases de duche, banheiras

6. **Ferragens e Fechaduras** (5 produtos)
   - Fechaduras, dobradiças, puxadores, cilindros

7. **Revestimentos** (4 produtos)
   - Azulejos, mosaicos, revestimentos

8. **Aquecimento** (4 produtos)
   - Radiadores, caldeiras, termostatos, toalheiros

9. **Iluminação** (4 produtos)
   - Lâmpadas LED, focos, candeeiros, projetores

10. **Ferramentas** (5 produtos)
    - Berbequins, martelos, níveis, alicates, serras

**Total: 10 categorias e 60 produtos**

## 🚀 Como Usar

### Opção 1: Via phpMyAdmin
1. Aceda ao phpMyAdmin
2. Selecione a base de dados `Paulimane`
3. Vá ao separador "SQL"
4. Cole o conteúdo de `sample_data.sql` ou `sample_data_simple.sql`
5. Clique em "Executar"

### Opção 2: Via Linha de Comandos
```bash
# Navegar até a pasta do projeto
cd /Users/leandromonteiro/Desktop/GitHub/Paulimane

# Executar o script
mysql -u root -p Paulimane < database/sample_data.sql
```

### Opção 3: Via MySQL Workbench
1. Abra o MySQL Workbench
2. Conecte-se ao servidor
3. File → Open SQL Script
4. Selecione `sample_data.sql`
5. Execute o script (⚡ ícone)

## ⚠️ Notas Importantes

### Imagens
Os caminhos das imagens são **placeholders**. Terá de:
1. Adicionar imagens reais nas pastas:
   - `/backoffice/uploads/catalogo/` (para categorias)
   - `/backoffice/uploads/produtos/` (para produtos)
2. Ou atualizar os caminhos na base de dados após inserir

### IDs das Categorias
O script assume que as categorias serão inseridas com IDs sequenciais (1-10).
Se já existirem categorias, ajuste os `CategoriaID` nos produtos.

### Limpar Dados Existentes
Se quiser limpar os dados antes de inserir, descomente estas linhas no início do `sample_data.sql`:
```sql
DELETE FROM Destaques;
DELETE FROM Produtos;
DELETE FROM Categoria;
```

## 🔍 Verificar Inserções

Após executar o script, pode verificar com:

```sql
-- Contar categorias
SELECT COUNT(*) as Total FROM Categoria;

-- Contar produtos
SELECT COUNT(*) as Total FROM Produtos;

-- Produtos por categoria
SELECT c.Nome as Categoria, COUNT(p.ID) as Total 
FROM Categoria c 
LEFT JOIN Produtos p ON c.ID = p.CategoriaID 
GROUP BY c.ID, c.Nome;
```

## 📝 Personalização

Para adicionar mais produtos ou categorias, siga o formato:

```sql
-- Categoria
INSERT INTO Categoria (Imagem, Nome, Descricao) VALUES
('caminho/imagem.jpg', 'Nome Categoria', 'Descrição da categoria');

-- Produto
INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('caminho/imagem.jpg', 'Nome Produto', 'Descrição do produto', ID_DA_CATEGORIA);
```

## 🎯 Produtos em Destaque

Após inserir os produtos, pode adicionar alguns aos destaques:

```sql
-- Adicionar 6 produtos aos destaques (ajuste os IDs conforme necessário)
INSERT INTO Destaques (ProdutoID) VALUES
(1), (7), (15), (22), (30), (40);
```

---

**Criado para:** Paulimane - Materiais de Construção  
**Data:** Novembro 2025
