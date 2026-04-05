// ============================================
// INICIALIZAÇÃO DO APP
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeMobileToggle();
    initializeTooltips();
    loadNotifications();
    console.log('Dashboard AutoVendas carregado com sucesso!');
});



function voltarPagina(page) {
    window.history.back();
}

// ============================================
// SIDEBAR
// ============================================

function initializeSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    // Restaurar estado do sidebar
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true') {
        sidebar.classList.add('collapsed');
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const isCollapsed = sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

// ============================================
// MOBILE MENU
// ============================================

function initializeMobileToggle() {
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');

    if (mobileToggle && window.innerWidth <= 768) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Fechar sidebar ao clicar em um link
        document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                }
            });
        });
    }
}

// ============================================
// NOTIFICAÇÕES
// ============================================

function loadNotifications() {
    // As notificações já estão no HTML, mas aqui você pode carregar via API
    console.log('Notificações carregadas');
}

function clearNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    if (dropdown) {
        const items = dropdown.querySelectorAll('.dropdown-item');
        items.forEach((item, index) => {
            if (index > 0) { // Manter o header e divider
                item.remove();
            }
        });
        const badge = document.querySelector('.badge');
        if (badge) {
            badge.textContent = '0';
        }
    }
}

function showNotification(title, message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    console.log(toastContainer);
    if (!toastContainer) return;

    const toast = document.createElement('div');
    toast.className = `toast show`;
    toast.setAttribute('role', 'alert');
    
    const bgClass = {
        'info': 'bg-info',
        'success': 'bg-success',
        'warning': 'bg-warning',
        'danger': 'bg-danger'
    }[type] || 'bg-info';

    toast.innerHTML = `
        <div class="toast-header ${bgClass} text-white">
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    toastContainer.appendChild(toast);

    // Auto-remover após 5 segundos
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// ============================================
// MODAIS - VEÍCULOS
// ============================================

let currentVehicleId = null;

function editVehicle(id) {
    currentVehicleId = id;
    // Aqui você carregaria os dados do veículo
    console.log('Editando veículo:', id);
}

function deleteVehicle(id) {
    currentVehicleId = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}

function confirmDelete() {
    if (currentVehicleId) {
        showNotification('Sucesso', 'Veículo removido com sucesso!', 'success');
        // Aqui você faria a requisição DELETE via API
        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        // Recarregar tabela
        location.reload();
    }
}

function saveVehicle() {
    // Aqui você faria a requisição POST via API
    showNotification('Sucesso', 'Veículo adicionado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('addVehicleModal')).hide();
    // Recarregar tabela
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function updateVehicle() {
    // Aqui você faria a requisição PUT via API
    showNotification('Sucesso', 'Veículo atualizado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('editVehicleModal')).hide();
    // Recarregar tabela
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// MODAIS - CLIENTES
// ============================================

let currentClientId = null;

function deleteClient(id) {
    currentClientId = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}

function saveClient() {
    showNotification('Sucesso', 'Cliente adicionado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('addClientModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function updateClient() {
    showNotification('Sucesso', 'Cliente atualizado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('editClientModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// MODAIS - VENDAS
// ============================================

function saveSale() {
    showNotification('Sucesso', 'Venda registrada com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('addSaleModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function updateSale() {
    showNotification('Sucesso', 'Venda atualizada com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('editSaleModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// RELATÓRIOS
// ============================================

function generateReport(type) {
    showNotification('Gerando Relatório', `Relatório de ${type} está sendo gerado...`, 'info');
    console.log(`Gerando relatório de ${type}...`);
    
    // Simular geração de relatório
    setTimeout(() => {
        showNotification('Sucesso', `Relatório de ${type} gerado com sucesso!`, 'success');
        // Aqui você poderia fazer download do arquivo
    }, 2000);
}

function createCustomReport() {
    showNotification('Sucesso', 'Relatório personalizado criado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('customReportModal')).hide();
}

// ============================================
// PUBLICAÇÃO WEB
// ============================================

function publishVehicle() {
    showNotification('Sucesso', 'Veículo publicado na web com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('publishModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function unpublishVehicle(id) {
    showNotification('Sucesso', 'Veículo removido da publicação!', 'success');
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// PERFIL
// ============================================

function saveProfile() {
    showNotification('Sucesso', 'Perfil atualizado com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function saveStand() {
    showNotification('Sucesso', 'Informações da stand atualizadas!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('editStandModal')).hide();
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function changePassword() {
    showNotification('Sucesso', 'Senha alterada com sucesso!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
}

// ============================================
// CONFIGURAÇÕES
// ============================================

function saveSetting(key, value) {
    localStorage.setItem(`setting_${key}`, value);
    console.log(`Configuração ${key} salva:`, value);
}

function saveAllSettings() {
    showNotification('Sucesso', 'Configurações salvas com sucesso!', 'success');
}

function backupNow() {
    showNotification('Processando', 'Criando backup dos dados...', 'info');
    
    setTimeout(() => {
        showNotification('Sucesso', 'Backup criado com sucesso!', 'success');
    }, 3000);
}

function exportData() {
    showNotification('Processando', 'Exportando dados...', 'info');
    
    setTimeout(() => {
        showNotification('Sucesso', 'Dados exportados com sucesso! Arquivo baixado.', 'success');
    }, 2000);
}

function deleteAccount() {
    if (confirm('⚠️ AVISO: Você tem certeza que deseja deletar sua conta? Esta ação é IRREVERSÍVEL!')) {
        showNotification('Processando', 'Deletando conta...', 'warning');
        setTimeout(() => {
            showNotification('Sucesso', 'Conta deletada com sucesso!', 'success');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        }, 2000);
    }
}

// ============================================
// LOGOUT
// ============================================

function logout() {
    if (confirm('Tem certeza que deseja sair?')) {
        showNotification('Saindo...', 'Encerrando sua sessão...', 'info');
        
        setTimeout(() => {
            // Aqui você faria a requisição de logout
            window.location.href = '/login';
        }, 1500);
    }
}

// ============================================
// TOOLTIPS E POPOVERS
// ============================================

function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// ============================================
// UTILITÁRIOS
// ============================================

// Formatar moeda
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Formatar data
function formatDate(date) {
    return new Intl.DateTimeFormat('pt-BR').format(new Date(date));
}

// Buscar em tabela
function filterTable(searchId, tableId) {
    const searchInput = document.getElementById(searchId);
    const table = document.getElementById(tableId);
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
}

// ============================================
// RESPONSIVIDADE
// ============================================

// Verificar mudança de tamanho de tela
let isMobile = window.innerWidth <= 768;

window.addEventListener('resize', function() {
    const wasMobile = isMobile;
    isMobile = window.innerWidth <= 768;
    
    if (wasMobile !== isMobile) {
        location.reload(); // Recarregar para ajustar layout
    }
});

// ============================================
// VALIDAÇÃO DE FORMULÁRIOS
// ============================================

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// ============================================
// BUSCA E FILTROS
// ============================================

// Exemplo de busca em tempo real
function setupSearch(inputSelector, tableSelector) {
    const searchInput = document.querySelector(inputSelector);
    const table = document.querySelector(tableSelector);
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

// ============================================
// CONFIRMAR AÇÕES PERIGOSAS
// ============================================

function confirmAction(message = 'Tem certeza que deseja continuar?') {
    return confirm(message);
}

// ============================================
// SISTEMA DE CACHE LOCAL
// ============================================

const Cache = {
    set: function(key, value, expiryMinutes = 60) {
        const expiryTime = new Date().getTime() + (expiryMinutes * 60 * 1000);
        localStorage.setItem(key, JSON.stringify({
            value: value,
            expiry: expiryTime
        }));
    },
    
    get: function(key) {
        const item = localStorage.getItem(key);
        if (!item) return null;
        
        const data = JSON.parse(item);
        if (new Date().getTime() > data.expiry) {
            localStorage.removeItem(key);
            return null;
        }
        
        return data.value;
    },
    
    remove: function(key) {
        localStorage.removeItem(key);
    },
    
    clear: function() {
        localStorage.clear();
    }
};

// ============================================
// DEBUG MODE
// ============================================

const DEBUG = true;

function log(message, data = null) {
    if (DEBUG) {
        console.log('[AutoVendas]', message, data || '');
    }
}

log('App iniciado com sucesso');
