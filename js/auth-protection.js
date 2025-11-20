/**
 * Sistema de Proteção de Páginas
 * Adicione este script em todas as páginas que precisam de autenticação
 */

(function() {
    // Verificar se está autenticado
    const authToken = sessionStorage.getItem('paulimane_site_auth');
    
    if (!authToken) {
        // Público: sem redirecionamento
        return;
    }

    // Verificar se token ainda é válido
    async function checkAuth() {
        try {
            const response = await fetch('backoffice/api/check_auth.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (!data.success || !data.authenticated) {
                sessionStorage.clear();
                return;
            }

            // Atualizar informações do utilizador
            if (data.user) {
                sessionStorage.setItem('paulimane_site_user', JSON.stringify(data.user));
            }

        } catch (error) {
            console.error('Erro ao verificar autenticação:', error);
        }
    }

    // Verificar autenticação ao carregar
    checkAuth();

    // Mostrar informações do utilizador no console
    const user = JSON.parse(sessionStorage.getItem('paulimane_site_user') || '{}');
    if (user.nome) {
        console.log(`%c👤 Bem-vindo, ${user.nome}!`, 'color: #F26522; font-weight: bold; font-size: 14px;');
    }
})();
