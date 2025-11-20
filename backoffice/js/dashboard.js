// Check authentication
window.addEventListener('DOMContentLoaded', async () => {
    const authToken = sessionStorage.getItem('paulimane_auth_token');
    

    try {
        // Verificar se token ainda é válido
        const response = await fetch('api/check_auth.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (!data.success || !data.authenticated) {
            sessionStorage.clear();
        }

        // Atualizar informações do utilizador na interface
        const userName = document.querySelector('.user-name');
        const userAvatar = document.querySelector('.user-avatar');
        
        if (userName && data.user) {
            userName.textContent = data.user.nome;
        }
        
        // Atualizar avatar com foto de perfil
        if (userAvatar && data.user) {
            if (data.user.imagem) {
                // Se tem imagem, mostrar a foto
                userAvatar.innerHTML = `<img src="${data.user.imagem}" alt="${data.user.nome}">`;
            }
            // Se não tem imagem, manter o ícone SVG padrão que já está no HTML
        }

    } catch (error) {
        console.error('Erro ao verificar autenticação:', error);
        // Em caso de erro, permitir continuar sem redirecionar
    }
});

// Mobile menu toggle
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        
        // Create overlay if it doesn't exist
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
            
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
        
        overlay.classList.toggle('active');
    });
}

// Logout functionality
const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        
        // Confirm logout
        if (confirm('Tem a certeza que deseja sair?')) {
            try {
                // Chamar API de logout
                await fetch('/backoffice/api/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Erro ao fazer logout:', error);
            }
            
            // Clear session storage
            sessionStorage.clear();
            localStorage.removeItem('paulimane_remember');
            localStorage.removeItem('paulimane_username');
            
            // Redirect to backoffice login
            window.location.href = 'login.html';
        }
    });
}

// Close sidebar when clicking nav items on mobile
const navItems = document.querySelectorAll('.nav-item');
navItems.forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth <= 1024) {
            sidebar.classList.remove('active');
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    });
});

// Handle window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('active');
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    }, 250);
});
