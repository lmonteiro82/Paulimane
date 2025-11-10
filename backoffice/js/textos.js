/**
 * Gestão de Textos do Site
 * Paulimane Backoffice
 */

// Elementos
const sobrenosForm = document.getElementById('sobrenosForm');
const sobrenosTexto = document.getElementById('sobrenosTexto');
const charCount = document.getElementById('charCount');
const preview = document.getElementById('preview');
const successAlert = document.getElementById('successAlert');
const errorAlert = document.getElementById('errorAlert');
const successMessage = document.getElementById('successMessage');
const errorMessage = document.getElementById('errorMessage');
const btnSave = document.getElementById('btnSave');

// Elementos das estatísticas
const estatisticasForm = document.getElementById('estatisticasForm');
const btnSaveStats = document.getElementById('btnSaveStats');
const numero1 = document.getElementById('numero1');
const numero2 = document.getElementById('numero2');
const numero3 = document.getElementById('numero3');
const numero_texto1 = document.getElementById('numero_texto1');
const numero_texto2 = document.getElementById('numero_texto2');
const numero_texto3 = document.getElementById('numero_texto3');

// Carregar texto ao iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadTexto();
    loadEstatisticas();
    setupPreview();
});

// Configurar pré-visualização em tempo real
function setupPreview() {
    sobrenosTexto.addEventListener('input', () => {
        updateCharCount();
        updatePreview();
    });
}

function updateCharCount() {
    const length = sobrenosTexto.value.length;
    charCount.textContent = `${length} / 2000 caracteres`;
    
    if (length > 1800) {
        charCount.style.color = '#dc3545';
    } else if (length > 1500) {
        charCount.style.color = '#ffc107';
    } else {
        charCount.style.color = '#999';
    }
}

function updatePreview() {
    const texto = sobrenosTexto.value.trim();
    
    if (!texto) {
        preview.innerHTML = '<p><em>O texto aparecerá aqui...</em></p>';
        return;
    }
    
    // Dividir em parágrafos (duas linhas em branco)
    const paragrafos = texto.split(/\n\n+/);
    
    preview.innerHTML = '';
    paragrafos.forEach(paragrafo => {
        if (paragrafo.trim()) {
            const p = document.createElement('p');
            // Converter quebras de linha simples em <br>
            p.innerHTML = escapeHtml(paragrafo.trim()).replace(/\n/g, '<br>');
            preview.appendChild(p);
        }
    });
}

// Carregar texto
async function loadTexto() {
    try {
        const response = await fetch('api/textos/get.php?chave=sobrenos');
        const data = await response.json();
        
        if (data.success && data.texto) {
            sobrenosTexto.value = data.texto;
            updateCharCount();
            updatePreview();
        }
    } catch (error) {
        console.error('Erro ao carregar texto:', error);
        showError('Erro ao carregar texto');
    }
}

// Submit do formulário
sobrenosForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const texto = sobrenosTexto.value.trim();
    
    if (!texto) {
        showError('Por favor, preencha o texto');
        return;
    }
    
    if (texto.length > 2000) {
        showError('O texto excede o limite de 2000 caracteres');
        return;
    }
    
    try {
        btnSave.disabled = true;
        btnSave.innerHTML = '<span>A guardar...</span>';
        
        const response = await fetch('api/textos/update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                chave: 'sobrenos',
                texto: texto
            })
        });
        
        const data = await response.json();
        
        btnSave.disabled = false;
        btnSave.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Guardar Alterações
        `;
        
        if (data.success) {
            showSuccess('Texto atualizado com sucesso! As alterações já estão visíveis no site.');
        } else {
            showError(data.message || 'Erro ao atualizar texto');
        }
    } catch (error) {
        console.error('Erro:', error);
        btnSave.disabled = false;
        btnSave.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Guardar Alterações
        `;
        showError('Erro ao conectar ao servidor');
    }
});

// Funções auxiliares
function showSuccess(message) {
    successMessage.textContent = message;
    successAlert.classList.add('show');
    errorAlert.classList.remove('show');
    
    setTimeout(() => {
        successAlert.classList.remove('show');
    }, 5000);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showError(message) {
    errorMessage.textContent = message;
    errorAlert.classList.add('show');
    successAlert.classList.remove('show');
    
    setTimeout(() => {
        errorAlert.classList.remove('show');
    }, 5000);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// ========== ESTATÍSTICAS ==========

// Carregar estatísticas
async function loadEstatisticas() {
    try {
        const chaves = ['numero1', 'numero2', 'numero3', 'numero_texto1', 'numero_texto2', 'numero_texto3'];
        
        for (const chave of chaves) {
            const response = await fetch(`api/textos/get.php?chave=${chave}`);
            const data = await response.json();
            
            if (data.success && data.texto) {
                document.getElementById(chave).value = data.texto;
            }
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

// Submit do formulário de estatísticas
estatisticasForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const dados = {
        numero1: numero1.value.trim(),
        numero2: numero2.value.trim(),
        numero3: numero3.value.trim(),
        numero_texto1: numero_texto1.value.trim(),
        numero_texto2: numero_texto2.value.trim(),
        numero_texto3: numero_texto3.value.trim()
    };
    
    // Validar campos
    for (const [key, value] of Object.entries(dados)) {
        if (!value) {
            showError(`Por favor, preencha todos os campos`);
            return;
        }
    }
    
    try {
        btnSaveStats.disabled = true;
        btnSaveStats.innerHTML = '<span>A guardar...</span>';
        
        // Guardar cada campo
        let allSuccess = true;
        for (const [chave, texto] of Object.entries(dados)) {
            const response = await fetch('api/textos/update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ chave, texto })
            });
            
            const data = await response.json();
            if (!data.success) {
                allSuccess = false;
                break;
            }
        }
        
        btnSaveStats.disabled = false;
        btnSaveStats.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Guardar Estatísticas
        `;
        
        if (allSuccess) {
            showSuccess('Estatísticas atualizadas com sucesso! As alterações já estão visíveis no site.');
        } else {
            showError('Erro ao atualizar algumas estatísticas');
        }
    } catch (error) {
        console.error('Erro:', error);
        btnSaveStats.disabled = false;
        btnSaveStats.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Guardar Estatísticas
        `;
        showError('Erro ao conectar ao servidor');
    }
});
