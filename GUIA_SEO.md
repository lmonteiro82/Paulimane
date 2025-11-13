# Guia de SEO - Paulimane

## Data: 12 de Novembro de 2025

## 📋 Resumo

Este guia explica todas as otimizações de SEO implementadas para que o site da Paulimane apareça nas pesquisas do Google quando alguém pesquisar por "paulimane" ou "ferragens".

---

## ✅ Alterações Implementadas

### 1. **Meta Tags SEO**

Adicionadas em todas as páginas principais:

#### `index.php` (Página Principal)
- ✅ Title otimizado: "Paulimane - Ferragens Manuel Carmo & Azevedo | Ferragens de Qualidade desde 2000"
- ✅ Meta description com palavras-chave
- ✅ Meta keywords: paulimane, ferragens, ferragens portugal, etc.
- ✅ Open Graph tags (Facebook/WhatsApp)
- ✅ Twitter Cards
- ✅ Canonical URL
- ✅ Structured Data (Schema.org) - LocalBusiness

#### `catalogo.html`
- ✅ Title otimizado: "Catálogo de Ferragens - Paulimane"
- ✅ Meta description específica
- ✅ Meta keywords relevantes
- ✅ Open Graph tags
- ✅ Canonical URL

### 2. **Arquivos Criados**

#### `sitemap.xml`
Mapa do site para o Google indexar todas as páginas:
- Página principal (prioridade 1.0)
- Catálogo (prioridade 0.9)
- Login (prioridade 0.3)

#### `robots.txt`
Instruções para os motores de busca:
- ✅ Permite indexação de páginas públicas
- ✅ Bloqueia backoffice e áreas administrativas
- ✅ Referência ao sitemap.xml

#### `.htaccess`
Otimizações de servidor:
- ✅ Compressão GZIP
- ✅ Cache de arquivos estáticos
- ✅ Redirecionamento HTTPS
- ✅ Proteção de arquivos sensíveis

### 3. **Structured Data (Schema.org)**

Adicionado JSON-LD com informações da empresa:
```json
{
    "@type": "LocalBusiness",
    "name": "Paulimane - Ferragens Manuel Carmo & Azevedo, Lda",
    "foundingDate": "2000",
    "description": "Empresa portuguesa especializada em ferragens..."
}
```

---

## 🚀 Próximos Passos OBRIGATÓRIOS

### 1. **Google Search Console** (ESSENCIAL)

Para o site aparecer no Google, você DEVE registrá-lo:

1. Acesse: https://search.google.com/search-console
2. Clique em "Adicionar propriedade"
3. Digite: `https://www.paulimane.pt`
4. Escolha método de verificação:
   - **Opção A - Tag HTML**: Copie a meta tag fornecida e adicione no `<head>` do index.php
   - **Opção B - Arquivo HTML**: Faça download do arquivo e coloque na raiz do site
   - **Opção C - DNS**: Adicione um registro TXT no DNS do domínio

5. Após verificar, envie o sitemap:
   - No Search Console, vá em "Sitemaps"
   - Adicione: `https://www.paulimane.pt/sitemap.xml`
   - Clique em "Enviar"

### 2. **Google Business Profile** (RECOMENDADO)

Para aparecer no Google Maps e pesquisas locais:

1. Acesse: https://business.google.com
2. Crie perfil da empresa
3. Preencha todas as informações:
   - Nome: Paulimane - Ferragens Manuel Carmo & Azevedo, Lda
   - Categoria: Loja de Ferragens
   - Endereço completo
   - Telefone
   - Horário de funcionamento
   - Website: https://www.paulimane.pt
4. Adicione fotos da loja/produtos
5. Verifique o perfil (Google enviará carta com código)

### 3. **Atualizar Structured Data**

No arquivo `index.php`, linha 124, atualize com dados reais:

```javascript
"telephone": "+351-XXX-XXX-XXX",  // ← Colocar telefone real
"address": {
    "@type": "PostalAddress",
    "streetAddress": "Rua/Avenida XXXXX",  // ← Endereço completo
    "addressLocality": "Cidade",
    "postalCode": "XXXX-XXX",
    "addressCountry": "PT"
},
"openingHours": "Mo-Fr 09:00-18:00"  // ← Horário real
```

### 4. **Verificar URL do Site**

Em todos os arquivos, substitua `https://www.paulimane.pt/` pela URL real do site se for diferente.

Arquivos a verificar:
- `index.php` (linhas 86, 94, 100, 123)
- `catalogo.html` (linhas 15, 18, 21)
- `sitemap.xml` (todas as URLs)
- `robots.txt` (linha 22)

---

## 📊 Como Verificar se Está Funcionando

### 1. **Teste de Rich Results (Google)**
- Acesse: https://search.google.com/test/rich-results
- Cole a URL: `https://www.paulimane.pt`
- Verifique se o Structured Data está correto

### 2. **Teste de Compatibilidade Mobile**
- Acesse: https://search.google.com/test/mobile-friendly
- Cole a URL do site
- Corrija erros se houver

### 3. **PageSpeed Insights**
- Acesse: https://pagespeed.web.dev/
- Cole a URL do site
- Verifique pontuação (ideal: >90)

### 4. **Pesquisa Manual no Google**

Após 1-2 semanas:
```
site:paulimane.pt
```
Deve mostrar todas as páginas indexadas.

Pesquise também:
- `paulimane`
- `paulimane ferragens`
- `ferragens manuel carmo azevedo`

---

## 🎯 Palavras-Chave Otimizadas

O site está otimizado para as seguintes pesquisas:

### Principais:
- ✅ **paulimane**
- ✅ **ferragens**
- ✅ **ferragens portugal**
- ✅ **manuel carmo azevedo**

### Secundárias:
- ferragens qualidade
- ferragens industriais
- catálogo ferragens
- comércio ferragens
- ferragens desde 2000

---

## ⏱️ Tempo de Indexação

**Importante**: O Google não indexa sites instantaneamente!

- **Primeira indexação**: 1-4 semanas após enviar sitemap
- **Aparecer em pesquisas**: 2-8 semanas
- **Ranking melhorar**: 3-6 meses

### Para acelerar:
1. Envie sitemap no Google Search Console
2. Use "Solicitar indexação" no Search Console
3. Crie backlinks (links de outros sites para o seu)
4. Partilhe o site nas redes sociais
5. Adicione o site em diretórios de empresas portuguesas

---

## 📱 Redes Sociais (Recomendado)

Crie perfis e adicione link para o site:

1. **Facebook Business**: https://business.facebook.com
2. **LinkedIn**: Página da empresa
3. **Instagram Business**: Perfil comercial
4. **Google Business**: (já mencionado acima)

Isto cria backlinks e aumenta autoridade do domínio.

---

## 🔍 Monitorização Contínua

### Ferramentas Gratuitas:

1. **Google Search Console** (obrigatório)
   - Monitorar indexação
   - Ver queries de pesquisa
   - Identificar erros

2. **Google Analytics** (recomendado)
   - Instalar código de tracking
   - Ver visitantes e origem
   - Analisar comportamento

3. **Google Business Insights**
   - Ver quantas pessoas encontraram no Google
   - Ver pesquisas que levaram ao perfil

---

## ⚠️ Erros Comuns a Evitar

1. ❌ Não usar texto em imagens (Google não lê)
2. ❌ Não ter conteúdo duplicado
3. ❌ Não usar Flash ou tecnologias antigas
4. ❌ Site lento (otimizar imagens)
5. ❌ Não ter versão mobile responsiva
6. ❌ Links quebrados (erro 404)

---

## 📈 Melhorias Futuras (Opcional)

1. **Blog/Notícias**: Criar secção com artigos sobre ferragens
2. **FAQ**: Página com perguntas frequentes
3. **Testemunhos**: Adicionar reviews de clientes
4. **Certificações**: Mostrar certificados de qualidade
5. **Vídeos**: Adicionar vídeos de produtos no YouTube
6. **Multilíngue**: Versão em inglês/espanhol

---

## 📞 Suporte

Se tiver dúvidas sobre SEO:
- Google Search Central: https://developers.google.com/search
- Fórum de Ajuda: https://support.google.com/webmasters

---

## ✅ Checklist Final

Antes de considerar o SEO completo:

- [ ] Registar site no Google Search Console
- [ ] Enviar sitemap.xml
- [ ] Verificar propriedade do site
- [ ] Atualizar dados de contacto no Structured Data
- [ ] Criar Google Business Profile
- [ ] Verificar todas as URLs estão corretas
- [ ] Testar site em mobile
- [ ] Verificar velocidade do site
- [ ] Criar perfis em redes sociais
- [ ] Adicionar site em diretórios portugueses
- [ ] Aguardar 2-4 semanas e verificar indexação

---

**IMPORTANTE**: O SEO é um processo contínuo. Os resultados não são imediatos, mas com estas otimizações, o site da Paulimane tem tudo para aparecer bem posicionado nas pesquisas do Google! 🚀
