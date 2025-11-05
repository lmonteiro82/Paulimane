# Backoffice Paulimane

Sistema de gestão backoffice para o website da Paulimane com autenticação via base de dados MySQL.

## 🚀 Instalação Rápida

1. **Configurar Base de Dados:**
   ```bash
   mysql -u root -p < config/setup.sql
   ```
   Password do MySQL: `senha123`

2. **Iniciar Servidor PHP:**
   ```bash
   php -S localhost:8000
   ```

3. **Aceder ao Backoffice:**
   `http://localhost:8000/backoffice/login.html`

📖 **Guia completo:** Veja `INSTALACAO.md` para instruções detalhadas.

## 🔐 Credenciais de Acesso

Todos os utilizadores têm a password: **paulimane2024**

| Username    | Nível de Acesso | Email                      |
|-------------|-----------------|----------------------------|
| admin       | Administrador   | admin@paulimane.pt         |
| gestor      | Gestor          | manuel.carmo@paulimane.pt  |
| funcionario | Funcionário     | ana.azevedo@paulimane.pt   |

## 📁 Estrutura de Ficheiros

```
backoffice/
├── config/
│   ├── database.php          # Configuração da base de dados
│   ├── setup.sql             # Script SQL de criação
│   └── generate_password.php # Gerar hash de passwords
├── api/
│   ├── login.php             # API de autenticação
│   ├── logout.php            # API de logout
│   └── check_auth.php        # Verificar sessão
├── css/
│   ├── login.css             # Estilos do login
│   └── dashboard.css         # Estilos do dashboard
├── js/
│   ├── login.js              # Lógica de login
│   └── dashboard.js          # Lógica do dashboard
├── login.html                # Página de login
├── dashboard.html            # Dashboard principal
├── README.md                 # Este ficheiro
└── INSTALACAO.md             # Guia de instalação
```

## 🚀 Funcionalidades

### Página de Login
- ✅ Design moderno e responsivo
- ✅ **Autenticação via base de dados MySQL**
- ✅ **Validação de credenciais com password hash**
- ✅ Toggle de visibilidade da password
- ✅ Opção "Lembrar-me"
- ✅ Mensagens de erro dinâmicas
- ✅ Loading state no botão
- ✅ Link para voltar ao site principal
- ✅ **Sistema de sessões seguro**

### Dashboard
- ✅ Sidebar com navegação
- ✅ Cards de estatísticas
- ✅ Ações rápidas
- ✅ Design responsivo
- ✅ Menu mobile
- ✅ **Logout com limpeza de sessão na BD**
- ✅ **Proteção de rota com verificação de token**
- ✅ **Exibição do nome do utilizador autenticado**

### Base de Dados
- ✅ **Tabela `utilizador`** - Gestão de utilizadores
- ✅ **Tabela `sessoes`** - Tokens de sessão ativos
- ✅ **Tabela `logs_acesso`** - Registo de acessos
- ✅ **Passwords com hash bcrypt**
- ✅ **Níveis de acesso** (admin, gestor, funcionario)
- ✅ **Controlo de utilizadores ativos/inativos**

## 🔒 Segurança

### ✅ Implementado
- ✅ **Autenticação via base de dados MySQL**
- ✅ **Passwords com hash bcrypt (password_hash)**
- ✅ **Sessões com tokens únicos**
- ✅ **Validação de credenciais no servidor**
- ✅ **Logs de acesso (sucesso e falha)**
- ✅ **Expiração automática de sessões (24h)**
- ✅ **Proteção contra SQL Injection (PDO prepared statements)**

### ⚠️ Recomendações para Produção
1. **Usar HTTPS** para todas as comunicações
2. **Adicionar rate limiting** para prevenir ataques de força bruta
3. **Implementar CSRF tokens**
4. **Configurar headers de segurança** (X-Frame-Options, CSP, etc.)
5. **Backup regular da base de dados**
6. **Monitorizar logs de acesso**
7. **Alterar credenciais padrão**

## 📱 Responsividade

O sistema é totalmente responsivo e funciona em:
- 💻 Desktop (1920px+)
- 💻 Laptop (1024px - 1920px)
- 📱 Tablet (768px - 1024px)
- 📱 Mobile (< 768px)

## 🎨 Personalização

### Cores
As cores principais podem ser alteradas no ficheiro CSS através das variáveis CSS:

```css
:root {
    --primary-color: #F26522;
    --primary-dark: #D95518;
    /* ... outras variáveis */
}
```

### Logo
Substitua o ficheiro `../images/logo.png` pelo logo da sua empresa.

## 🔄 Próximos Passos

Para expandir o sistema, considere adicionar:

1. **Gestão de Produtos**
   - CRUD completo de produtos
   - Upload de imagens
   - Categorização

2. **Gestão de Clientes**
   - Lista de clientes
   - Histórico de compras
   - Contactos

3. **Relatórios**
   - Gráficos de vendas
   - Exportação para PDF/Excel
   - Análise de dados

4. **Configurações**
   - Gestão de utilizadores
   - Permissões
   - Configurações do site

## 📞 Suporte

Para questões ou suporte, contacte:
- Email: paulimane2000@gmail.com
- Telefone: 22 744 0671

---

**Desenvolvido para Paulimane - Ferragens Manuel Carmo & Azevedo, Lda.**
