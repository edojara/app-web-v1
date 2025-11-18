<div class="container mt-4">
    <div class="content-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <input 
            type="text" 
            id="searchInput" 
            class="form-control" 
            placeholder="Buscar por nombre, RUT, institución..." 
            onkeyup="filterTable()"
            style="flex: 1; max-width: 50%;">
        <a href="/?url=participantes/create" class="btn btn-primary" style="white-space: nowrap;">
            ➕ Nuevo Participante
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-header">
                <div class="column-header" onclick="sortTable('numero')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>#</span>
                    <span class="sort-icon" id="sort-numero">↕️</span>
                </div>
                <div class="column-header" onclick="sortTable('nombre')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>Nombre Completo</span>
                    <span class="sort-icon" id="sort-nombre">↕️</span>
                </div>
                <div class="column-header" onclick="sortTable('rut')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>RUT</span>
                    <span class="sort-icon" id="sort-rut">↕️</span>
                </div>
                <div class="column-header" onclick="sortTable('institucion')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>Institución</span>
                    <span class="sort-icon" id="sort-institucion">↕️</span>
                </div>
                <div class="column-header" onclick="sortTable('telefono')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>Teléfono</span>
                    <span class="sort-icon" id="sort-telefono">↕️</span>
                </div>
                <div class="column-header">Acciones</div>
            </div>

            <?php if (empty($participantes)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No hay participantes registrados</p>
                    <a href="/?url=participantes/create" class="btn btn-primary">Crear primer participante</a>
                </div>
            <?php else: ?>
                <?php $numero = 1; ?>
                <?php foreach ($participantes as $participante): ?>
                    <div class="table-row" 
                         data-nombre="<?= htmlspecialchars(strtolower($participante['nombre_completo'])) ?>"
                         data-rut="<?= htmlspecialchars(strtolower($participante['rut'])) ?>"
                         data-institucion="<?= htmlspecialchars(strtolower($participante['institucion_nombre'] ?? '')) ?>"
                         data-telefono="<?= htmlspecialchars(strtolower($participante['telefono'] ?? '')) ?>"
                         data-visible="true">
                        <div class="row-numero"><?= $numero++ ?></div>
                        <div><?= htmlspecialchars($participante['nombre_completo']) ?></div>
                        <div><?= htmlspecialchars($participante['rut']) ?></div>
                        <div>
                            <?php if ($participante['institucion_nombre']): ?>
                                <a href="/?url=instituciones/view&id=<?= $participante['institucion_id'] ?>">
                                    <?= htmlspecialchars($participante['institucion_nombre']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Sin institución</span>
                            <?php endif; ?>
                        </div>
                        <div><?= htmlspecialchars($participante['telefono'] ?? '-') ?></div>
                        <div class="action-buttons" style="text-align: center; white-space: normal;">
                            <a href="/?url=participantes/view&id=<?= $participante['id'] ?>" 
                               class="btn-action btn-view" 
                               title="Ver detalle">👁️</a>
                            <a href="/?url=participantes/edit&id=<?= $participante['id'] ?>" 
                               class="btn-action btn-edit" 
                               title="Editar">✏️</a>
                            <a href="/?url=participantes/delete&id=<?= $participante['id'] ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('¿Está seguro de eliminar este participante?')"
                               title="Eliminar">🗑️</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($participantes)): ?>
        <div class="pagination-container" style="margin-top: 1.5rem;">
            <div class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <label for="recordsPerPage">Mostrar: </label>
                    <select id="recordsPerPage" onchange="changeRecordsPerPage()" class="form-control" style="display: inline-block; width: auto;">
                        <option value="10">10</option>
                        <option value="20" selected>20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">Todos</option>
                    </select>
                    <span id="recordsInfo" style="margin-left: 1rem;"></span>
                </div>
                <div id="paginationButtons"></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.table-header {
    display: grid;
    grid-template-columns: 60px minmax(200px, 2fr) minmax(120px, 1fr) minmax(200px, 2fr) minmax(120px, 1fr) 280px;
    gap: 1rem;
    padding: 1rem;
    background-color: #f8f9fa;
    font-weight: bold;
    border-bottom: 2px solid #dee2e6;
}

.table-row {
    display: grid;
    grid-template-columns: 60px minmax(200px, 2fr) minmax(120px, 1fr) minmax(200px, 2fr) minmax(120px, 1fr) 280px;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    align-items: center;
}

.table-row:hover {
    background-color: #f8f9fa;
}

.column-header {
    cursor: pointer;
    user-select: none;
}

.column-header:hover {
    color: #007bff;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.2s ease;
    margin: 0 0.15rem;
}

.btn-view {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-view:hover {
    background: #2196f3;
    color: white;
    transform: scale(1.1);
}

.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-edit:hover {
    background: #1976d2;
    color: white;
    transform: scale(1.1);
}

.btn-delete {
    background: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover {
    background: #d32f2f;
    color: white;
    transform: scale(1.1);
}
</style>

<script>
let currentPage = 1;
let recordsPerPage = 20;
let sortOrders = {
    numero: 'neutral',
    nombre: 'neutral',
    rut: 'neutral',
    institucion: 'neutral',
    telefono: 'neutral'
};

function sortTable(column) {
    const rows = Array.from(document.querySelectorAll('.table-row'));
    
    // Determinar orden
    let order = sortOrders[column];
    if (order === 'neutral' || order === 'desc') {
        order = 'asc';
    } else {
        order = 'desc';
    }
    
    // Resetear otros iconos
    Object.keys(sortOrders).forEach(key => {
        if (key !== column) {
            sortOrders[key] = 'neutral';
            const icon = document.getElementById('sort-' + key);
            if (icon) icon.textContent = '↕️';
        }
    });
    
    sortOrders[column] = order;
    
    // Actualizar icono
    const icon = document.getElementById('sort-' + column);
    if (icon) {
        icon.textContent = order === 'asc' ? '🔼' : '🔽';
    }
    
    // Ordenar
    rows.sort((a, b) => {
        let aVal, bVal;
        
        if (column === 'numero') {
            aVal = parseInt(a.querySelector('.row-numero').textContent);
            bVal = parseInt(b.querySelector('.row-numero').textContent);
        } else {
            aVal = a.getAttribute('data-' + column) || '';
            bVal = b.getAttribute('data-' + column) || '';
        }
        
        if (order === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });
    
    // Reordenar en el DOM
    const container = rows[0].parentNode;
    rows.forEach(row => container.appendChild(row));
    
    // Renumerar
    rows.forEach((row, index) => {
        row.querySelector('.row-numero').textContent = index + 1;
    });
    
    // Actualizar paginación
    updateDisplay();
}

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        const nombre = row.getAttribute('data-nombre') || '';
        const rut = row.getAttribute('data-rut') || '';
        const institucion = row.getAttribute('data-institucion') || '';
        
        const matches = nombre.includes(searchValue) || 
                       rut.includes(searchValue) || 
                       institucion.includes(searchValue);
        
        row.setAttribute('data-visible', matches ? 'true' : 'false');
    });
    
    currentPage = 1;
    updateDisplay();
}

function updateDisplay() {
    const rows = Array.from(document.querySelectorAll('.table-row'));
    const visibleRows = rows.filter(row => row.getAttribute('data-visible') === 'true');
    
    const totalRecords = visibleRows.length;
    const totalPages = recordsPerPage === 'all' ? 1 : Math.ceil(totalRecords / recordsPerPage);
    
    if (currentPage > totalPages) {
        currentPage = totalPages || 1;
    }
    
    const startIndex = recordsPerPage === 'all' ? 0 : (currentPage - 1) * recordsPerPage;
    const endIndex = recordsPerPage === 'all' ? totalRecords : startIndex + parseInt(recordsPerPage);
    
    rows.forEach(row => {
        row.style.display = 'none';
    });
    
    visibleRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = 'grid';
        }
    });
    
    const recordsInfo = document.getElementById('recordsInfo');
    if (recordsInfo) {
        recordsInfo.textContent = `Mostrando ${Math.min(startIndex + 1, totalRecords)} a ${Math.min(endIndex, totalRecords)} de ${totalRecords} registros`;
    }
    
    updatePaginationControls(totalPages, totalRecords);
}

function updatePaginationControls(totalPages, totalRecords) {
    const container = document.getElementById('paginationButtons');
    if (!container) return;
    
    if (totalRecords === 0 || recordsPerPage === 'all') {
        container.innerHTML = '';
        return;
    }
    
    let html = '<div class="btn-group" role="group">';
    
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>← Anterior</button>`;
    
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    
    if (endPage - startPage < maxButtons - 1) {
        startPage = Math.max(1, endPage - maxButtons + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}" onclick="goToPage(${i})">${i}</button>`;
    }
    
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Siguiente →</button>`;
    html += '</div>';
    
    container.innerHTML = html;
}

function goToPage(page) {
    const rows = Array.from(document.querySelectorAll('.table-row'));
    const visibleRows = rows.filter(row => row.getAttribute('data-visible') === 'true');
    const totalPages = Math.ceil(visibleRows.length / recordsPerPage);
    
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    updateDisplay();
}

function changeRecordsPerPage() {
    const select = document.getElementById('recordsPerPage');
    recordsPerPage = select.value === 'all' ? 'all' : parseInt(select.value);
    currentPage = 1;
    updateDisplay();
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    updateDisplay();
});
</script>
