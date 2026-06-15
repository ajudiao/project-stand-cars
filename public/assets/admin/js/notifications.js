/**
 * Script de atualização dinâmica de notificações
 * Atualiza as notificações a cada 30 segundos
 */

(function() {
    const NOTIFICATION_UPDATE_INTERVAL = 30000; // 30 segundos

    // Inicializar atualização de notificações
    function initNotifications() {
        // Atualizar notificações imediatamente e depois periodicamente
        updateNotifications();
        setInterval(updateNotifications, NOTIFICATION_UPDATE_INTERVAL);
    }

    // Função para atualizar notificações via AJAX
    function updateNotifications() {
        fetch('/admin/notificacoes/fetch?limit=5', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.notifications) {
                updateNotificationUI(data.notifications, data.count);
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar notificações:', error);
        });
    }

    // Função para atualizar a interface com as novas notificações
    function updateNotificationUI(notifications, count) {
        const badgeElement = document.querySelector('#notificationsBtn .badge');
        const dropdownMenu = document.querySelector('#notificationsDropdown');
        
        if (!dropdownMenu) return;

        // Atualizar badge com o novo número
        if (badgeElement) {
            badgeElement.textContent = count;
            if (count === 0) {
                badgeElement.style.display = 'none';
            } else {
                badgeElement.style.display = 'inline-block';
            }
        } else if (count > 0) {
            // Criar badge se não existir
            const badge = document.createElement('span');
            badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
            badge.textContent = count;
            document.getElementById('notificationsBtn').appendChild(badge);
        }

        // Atualizar lista de notificações
        const notificationsList = dropdownMenu.querySelector('li:nth-child(2)');
        if (notificationsList) {
            if (notifications.length === 0) {
                notificationsList.innerHTML = '<li class="dropdown-item text-center text-muted py-3">Sem notificações</li>';
            } else {
                let html = '';
                notifications.forEach(item => {
                    html += `
                        <li>
                            <a class="dropdown-item" href="${item.url}">
                                <div class="notification-item">
                                    <i class="${item.icon} text-${item.badge}"></i>
                                    <div>
                                        <strong>${item.title}</strong>
                                        <p>${item.message}</p>
                                        <small class="text-muted">${item.timeAgo}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                    `;
                });
                notificationsList.innerHTML = html;
            }
        }

        // Atualizar header de notificações
        const headerElement = dropdownMenu.querySelector('.dropdown-header');
        if (headerElement) {
            headerElement.innerHTML = `
                Notificações (${count})
                <button class="btn btn-sm float-end" onclick="clearNotifications()">
                    <i class="bi bi-x"></i>
                </button>
            `;
        }
    }

    // Função para limpar notificações (se necessário)
    function clearNotifications() {
        // Implementar se necessário
        console.log('Limpar notificações');
    }

    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNotifications);
    } else {
        initNotifications();
    }

    // Expor funções globais
    window.updateNotifications = updateNotifications;
    window.clearNotifications = clearNotifications;
})();
