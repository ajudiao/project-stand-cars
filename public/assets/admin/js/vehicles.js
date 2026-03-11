// ============================================
// GERENCIAMENTO DE VEÍCULOS
// ============================================

// Dados fictícios de veículos
const vehicles = [
    {
        id: 1,
        brand: 'Toyota',
        model: 'Corolla 2024',
        year: 2024,
        color: 'Branco',
        type: 'Automático',
        price: 95000,
        mileage: 5000,
        status: 'Disponível',
        description: 'Excelente estado, primeira mão'
    },
    {
        id: 2,
        brand: 'Honda',
        model: 'Civic 2023',
        year: 2023,
        color: 'Preto',
        type: 'Manual',
        price: 87500,
        mileage: 15000,
        status: 'Em Negociação',
        description: 'Bem cuidado, revisões em dia'
    },
    {
        id: 3,
        brand: 'Fiat',
        model: 'Argo 2022',
        year: 2022,
        color: 'Prata',
        type: 'Automático',
        price: 45000,
        mileage: 32000,
        status: 'Disponível',
        description: 'Económico, perfeito para cidade'
    },
    {
        id: 4,
        brand: 'Volkswagen',
        model: 'Gol 2021',
        year: 2021,
        color: 'Vermelho',
        type: 'Manual',
        price: 38500,
        mileage: 48000,
        status: 'Vendido',
        description: 'Veículo robusto e confiável'
    }
];

// Inicializar página
document.addEventListener('DOMContentLoaded', function() {
    setupVehicleFilters();
    setupVehicleSearch();
});

// ============================================
// FILTROS E BUSCA
// ============================================

function setupVehicleFilters() {
    const filterStatus = document.querySelectorAll('select')[0];
    const filterBrand = document.querySelectorAll('select')[1];
    
    if (filterStatus) {
        filterStatus.addEventListener('change', filterVehicles);
    }
    if (filterBrand) {
        filterBrand.addEventListener('change', filterVehicles);
    }
}

function setupVehicleSearch() {
    const searchInput = document.querySelector('input[placeholder*="Buscar"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterVehicles();
        });
    }
}

function filterVehicles() {
    const searchTerm = document.querySelector('input[placeholder*="Buscar"]')?.value.toLowerCase() || '';
    const statusFilter = document.querySelectorAll('select')[0]?.value || '';
    const brandFilter = document.querySelectorAll('select')[1]?.value || '';
    
    const rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(row => {
        let show = true;
        const text = row.textContent.toLowerCase();
        
        // Buscar por texto
        if (searchTerm && !text.includes(searchTerm)) {
            show = false;
        }
        
        // Filtrar por status
        if (statusFilter) {
            const statusCell = row.querySelectorAll('td')[4];
            const statusText = statusCell?.textContent.toLowerCase() || '';
            const statusMap = {
                '1': 'disponível',
                '2': 'negociação',
                '3': 'vendido'
            };
            if (!statusText.includes(statusMap[statusFilter])) {
                show = false;
            }
        }
        
        // Filtrar por marca
        if (brandFilter) {
            const modelCell = row.querySelectorAll('td')[1];
            const modelText = modelCell?.textContent.toLowerCase() || '';
            const brandMap = {
                '1': 'toyota',
                '2': 'honda',
                '3': 'fiat',
                '4': 'volkswagen'
            };
            if (!modelText.includes(brandMap[brandFilter])) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// ============================================
// EDITAR VEÍCULO
// ============================================

function editVehicle(id) {
    const vehicle = vehicles.find(v => v.id === id);
    if (!vehicle) return;
    
    const form = document.getElementById('editVehicleForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs[0].value = vehicle.brand;
    inputs[1].value = vehicle.model;
    inputs[2].value = vehicle.year;
    inputs[3].value = vehicle.color;
    inputs[4].value = vehicle.type === 'Automático' ? '1' : '2';
    inputs[5].value = vehicle.price;
    inputs[6].value = vehicle.status === 'Disponível' ? '1' : vehicle.status === 'Em Negociação' ? '2' : '3';
    inputs[7].value = vehicle.mileage;
    inputs[8].value = vehicle.description;
    
    // Armazenar ID para uso posterior
    window.editingVehicleId = id;
}

// ============================================
// DELETAR VEÍCULO
// ============================================

function deleteVehicle(id) {
    window.vehicleToDelete = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}

function confirmDelete() {
    if (window.vehicleToDelete) {
        // Aqui você faria a requisição DELETE para a API
        console.log('Deletando veículo:', window.vehicleToDelete);
        
        showNotification('Sucesso', 'Veículo removido com sucesso!', 'success');
        
        // Remover do array local
        const index = vehicles.findIndex(v => v.id === window.vehicleToDelete);
        if (index > -1) {
            vehicles.splice(index, 1);
        }
        
        // Fechar modal e atualizar tabela
        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
}

// ============================================
// SALVAR NOVO VEÍCULO
// ============================================

function saveVehicle() {
    const form = document.getElementById('addVehicleForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    
    const newVehicle = {
        id: Math.max(...vehicles.map(v => v.id)) + 1,
        brand: inputs[0].value,
        model: inputs[1].value,
        year: parseInt(inputs[2].value),
        color: inputs[3].value,
        type: inputs[4].value === '1' ? 'Automático' : 'Manual',
        price: parseFloat(inputs[5].value),
        mileage: parseInt(inputs[6].value),
        description: inputs[7].value,
        status: 'Disponível',
        image: inputs[8].files[0] ? 'uploaded' : null
    };
    
    // Validação básica
    if (!newVehicle.brand || !newVehicle.model || !newVehicle.year) {
        showNotification('Erro', 'Por favor, preencha todos os campos obrigatórios!', 'danger');
        return;
    }
    
    // Aqui você faria a requisição POST para a API
    console.log('Salvando novo veículo:', newVehicle);
    
    vehicles.push(newVehicle);
    
    showNotification('Sucesso', 'Veículo adicionado com sucesso!', 'success');
    
    // Fechar modal e atualizar
    bootstrap.Modal.getInstance(document.getElementById('addVehicleModal')).hide();
    form.reset();
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// ATUALIZAR VEÍCULO
// ============================================

function updateVehicle() {
    const form = document.getElementById('editVehicleForm');
    if (!form || !window.editingVehicleId) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    
    const updatedVehicle = {
        id: window.editingVehicleId,
        brand: inputs[0].value,
        model: inputs[1].value,
        year: parseInt(inputs[2].value),
        color: inputs[3].value,
        type: inputs[4].value === '1' ? 'Automático' : 'Manual',
        price: parseFloat(inputs[5].value),
        mileage: parseInt(inputs[6].value),
        description: inputs[8].value,
        status: inputs[6].value === '1' ? 'Disponível' : inputs[6].value === '2' ? 'Em Negociação' : 'Vendido'
    };
    
    // Validação
    if (!updatedVehicle.brand || !updatedVehicle.model) {
        showNotification('Erro', 'Por favor, preencha todos os campos obrigatórios!', 'danger');
        return;
    }
    
    // Aqui você faria a requisição PUT para a API
    console.log('Atualizando veículo:', updatedVehicle);
    
    // Atualizar no array local
    const index = vehicles.findIndex(v => v.id === window.editingVehicleId);
    if (index > -1) {
        vehicles[index] = { ...vehicles[index], ...updatedVehicle };
    }
    
    showNotification('Sucesso', 'Veículo atualizado com sucesso!', 'success');
    
    bootstrap.Modal.getInstance(document.getElementById('editVehicleModal')).hide();
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// ============================================
// EXPORTAR DADOS DE VEÍCULOS
// ============================================

function exportVehicles() {
    const csv = convertToCSV(vehicles);
    downloadCSV(csv, 'veiculos.csv');
}

function convertToCSV(data) {
    const headers = ['ID', 'Marca', 'Modelo', 'Ano', 'Cor', 'Tipo', 'Preço', 'Quilometragem', 'Status'];
    const rows = data.map(v => [
        v.id,
        v.brand,
        v.model,
        v.year,
        v.color,
        v.type,
        v.price,
        v.mileage,
        v.status
    ]);
    
    let csv = headers.join(',') + '\n';
    rows.forEach(row => {
        csv += row.join(',') + '\n';
    });
    
    return csv;
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// ============================================
// IMPRIMIR DADOS
// ============================================

function printVehicles() {
    const printWindow = window.open('', '', 'height=400,width=800');
    
    let html = '<html><head><title>Relatório de Veículos</title>';
    html += '<style>table { border-collapse: collapse; width: 100%; }';
    html += 'th, td { border: 1px solid black; padding: 8px; text-align: left; }</style></head><body>';
    html += '<h2>Relatório de Veículos</h2>';
    html += '<table><tr><th>Marca</th><th>Modelo</th><th>Ano</th><th>Preço</th><th>Status</th></tr>';
    
    vehicles.forEach(v => {
        html += `<tr><td>${v.brand}</td><td>${v.model}</td><td>${v.year}</td><td>R$ ${v.price.toLocaleString('pt-BR')}</td><td>${v.status}</td></tr>`;
    });
    
    html += '</table></body></html>';
    
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.print();
}

// ============================================
// ESTATÍSTICAS
// ============================================

function getVehicleStats() {
    const stats = {
        total: vehicles.length,
        available: vehicles.filter(v => v.status === 'Disponível').length,
        negotiating: vehicles.filter(v => v.status === 'Em Negociação').length,
        sold: vehicles.filter(v => v.status === 'Vendido').length,
        totalValue: vehicles.reduce((sum, v) => sum + v.price, 0),
        averagePrice: vehicles.reduce((sum, v) => sum + v.price, 0) / vehicles.length
    };
    
    return stats;
}

// Log de inicialização
console.log('Módulo de Veículos carregado');
console.log('Veículos disponíveis:', vehicles.length);
