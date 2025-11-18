<div class="container-fluid" style="max-width: 98%; padding: 0 1rem;">
    <div class="content-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; margin-top: 1.5rem;">
        <input 
            type="text" 
            id="searchInput" 
            class="form-control" 
            placeholder="Buscar por nombre, RUT, institución, teléfono..." 
            style="flex: 1; max-width: 50%;">
        <a href="/?url=participantes/export" class="btn btn-success" style="white-space: nowrap;">
            📥 Exportar CSV
        </a>
        <a href="/?url=participantes/import" class="btn btn-info" style="white-space: nowrap;">
            📤 Importar CSV
        </a>
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
                <div class="column-header" onclick="sortTable('email')" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <span>Email</span>
                    <span class="sort-icon" id="sort-email">↕️</span>
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
                         data-id="<?= $participante['id'] ?>"
                         data-nombre="<?= htmlspecialchars(strtolower($participante['nombre_completo'])) ?>"
                         data-rut="<?= htmlspecialchars(strtolower($participante['rut'])) ?>"
                         data-institucion="<?= htmlspecialchars(strtolower($participante['institucion_nombre'] ?? '')) ?>"
                         data-email="<?= htmlspecialchars(strtolower($participante['email'] ?? '')) ?>"
                         data-visible="true"
                         ondblclick="window.location.href='/?url=participantes/view&id=<?= $participante['id'] ?>'"
                         style="cursor: pointer;">
                        <div class="row-numero"><?= $numero++ ?></div>
                        <div data-label="Nombre: "><?= htmlspecialchars($participante['nombre_completo']) ?></div>
                        <div data-label="RUT: "><?= htmlspecialchars($participante['rut']) ?></div>
                        <div data-label="Institución: ">
                            <?php if ($participante['institucion_nombre']): ?>
                                <a href="/?url=instituciones/view&id=<?= $participante['institucion_id'] ?>">
                                    <?= htmlspecialchars($participante['institucion_nombre']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Sin institución</span>
                            <?php endif; ?>
                        </div>
                        <div data-label="Email: "><?= htmlspecialchars($participante['email'] ?? '-') ?></div>
                        <div class="action-buttons" style="text-align: center; white-space: normal;" onclick="event.stopPropagation();" ondblclick="event.stopPropagation();">
                            <button onclick="event.stopPropagation(); editParticipante(<?= $participante['id'] ?>, '<?= htmlspecialchars($participante['nombre_completo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($participante['rut'], ENT_QUOTES) ?>', '<?= htmlspecialchars($participante['telefono'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($participante['email'] ?? '', ENT_QUOTES) ?>', <?= $participante['institucion_id'] ?? 'null' ?>)" 
                               class="btn-action btn-edit" 
                               title="Editar">✏️</button>
                            <a href="/?url=participantes/delete&id=<?= $participante['id'] ?>" 
                               class="btn-action btn-delete" 
                               onclick="event.stopPropagation(); return confirm('¿Está seguro de eliminar este participante?')"
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

<!-- Modal para editar participante -->
<div id="editParticipanteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 50px auto; padding: 0; border-radius: 8px; width: 90%; max-width: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 2px solid #e0e0e0; background-color: #2196f3; color: white; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0; font-size: 1.5rem;">✏️ Editar Participante</h2>
            <button onclick="closeEditModal()" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: white; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <form id="editParticipanteForm" method="POST" action="/?url=participantes/update">
                <input type="hidden" id="edit_participante_id" name="id">
                
                <div style="margin-bottom: 1rem;">
                    <label for="edit_nombre_completo" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Nombre Completo <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" 
                           id="edit_nombre_completo" 
                           name="nombre_completo" 
                           class="form-control" 
                           required
                           maxlength="255">
                </div>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label for="edit_rut" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            RUT <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               id="edit_rut" 
                               name="rut" 
                               class="form-control" 
                               required
                               maxlength="12"
                               pattern="[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]{1}"
                               title="Formato: 12.345.678-9">
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_telefono" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Teléfono
                        </label>
                        <input type="text" 
                               id="edit_telefono" 
                               name="telefono" 
                               class="form-control" 
                               maxlength="20">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit_email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Email
                    </label>
                    <input type="email" 
                           id="edit_email" 
                           name="email" 
                           class="form-control" 
                           maxlength="255">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit_institucion_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Institución
                    </label>
                    <select id="edit_institucion_id" name="institucion_id" class="form-control">
                        <option value="">Sin institución</option>
                        <?php foreach ($instituciones as $inst): ?>
                            <option value="<?= $inst['id'] ?>"><?= htmlspecialchars($inst['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">💾 Actualizar Participante</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table-header {
    display: grid;
    grid-template-columns: 50px 3fr 1.5fr 3fr 2.5fr 90px;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #f8f9fa;
    font-weight: bold;
    border-bottom: 2px solid #dee2e6;
}

.table-row {
    display: grid;
    grid-template-columns: 50px 3fr 1.5fr 3fr 2.5fr 90px;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    align-items: center;
}

.table-row > div {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-row > div.action-buttons {
    white-space: normal;
    overflow: visible;
}

.table-row:hover {
    background-color: #f8f9fa;
}

.table-row.hidden-row {
    display: none !important;
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
    border: none;
    cursor: pointer;
    background: transparent;
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

/* Responsive para móviles */
@media (max-width: 768px) {
    .content-header {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    
    .content-header input {
        max-width: 100% !important;
        margin-bottom: 0.5rem;
    }
    
    .content-header a {
        width: 100%;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .table-header {
        display: none;
    }
    
    .table-row {
        display: block;
        grid-template-columns: none !important;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 1rem;
        padding: 1rem;
        background: white;
    }
    
    .table-row.hidden-row {
        display: none !important;
    }
    
    .table-row > div {
        display: block;
        padding: 0.5rem 0;
        border-bottom: none !important;
        white-space: normal;
        overflow: visible;
        text-align: left !important;
    }
    
    .table-row > div:before {
        content: attr(data-label);
        font-weight: bold;
        display: inline-block;
        width: 120px;
        color: var(--primary-color);
    }
    
    .table-row > .row-numero {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-color);
        text-align: center !important;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e0e0e0 !important;
        margin-bottom: 0.5rem;
    }
    
    .table-row > .row-numero:before {
        content: '#';
        margin-right: 0.5rem;
    }
    
    .table-row > .action-buttons {
        text-align: center !important;
        padding-top: 1rem;
        border-top: 2px solid #e0e0e0 !important;
        margin-top: 0.5rem;
    }
    
    .table-row > .action-buttons:before {
        display: none;
    }
    
    .pagination-controls {
        flex-direction: column !important;
        gap: 1rem;
    }
    
    .pagination-controls > div {
        width: 100%;
        text-align: center;
    }
    
    #recordsInfo {
        display: block;
        margin-top: 0.5rem;
        margin-left: 0 !important;
    }
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
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) {
        console.error('searchInput no encontrado');
        return;
    }
    
    const searchValue = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.table-row');
    
    console.log('Filtrando con valor:', searchValue);
    console.log('Total de filas:', rows.length);
    
    let visibleCount = 0;
    rows.forEach(row => {
        const nombre = (row.getAttribute('data-nombre') || '').toLowerCase();
        const rut = (row.getAttribute('data-rut') || '').toLowerCase();
        const institucion = (row.getAttribute('data-institucion') || '').toLowerCase();
        const telefono = (row.getAttribute('data-telefono') || '').toLowerCase();
        
        const matches = nombre.includes(searchValue) || 
                       rut.includes(searchValue) || 
                       institucion.includes(searchValue) ||
                       telefono.includes(searchValue);
        
        row.setAttribute('data-visible', matches ? 'true' : 'false');
        if (matches) visibleCount++;
    });
    
    console.log('Filas visibles:', visibleCount);
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
    
    // Ocultar todas las filas usando clase
    rows.forEach(row => {
        row.classList.add('hidden-row');
    });
    
    // Mostrar solo las filas visibles en la página actual
    visibleRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.classList.remove('hidden-row');
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

function editParticipante(id, nombre, rut, telefono, email, institucionId) {
    // Abrir el modal
    const modal = document.getElementById('editParticipanteModal');
    const form = document.getElementById('editParticipanteForm');
    
    // Configurar el action del formulario
    form.action = '/?url=participantes/update&id=' + id;
    
    // Rellenar los campos con los datos actuales
    document.getElementById('edit_participante_id').value = id;
    document.getElementById('edit_nombre_completo').value = nombre;
    document.getElementById('edit_rut').value = rut;
    document.getElementById('edit_telefono').value = telefono;
    document.getElementById('edit_email').value = email;
    
    // Seleccionar la institución
    const institucionSelect = document.getElementById('edit_institucion_id');
    if (institucionId) {
        institucionSelect.value = institucionId;
    } else {
        institucionSelect.value = '';
    }
    
    // Mostrar el modal
    modal.style.display = 'block';
}

function closeEditModal() {
    const modal = document.getElementById('editParticipanteModal');
    const form = document.getElementById('editParticipanteForm');
    
    // Ocultar el modal
    modal.style.display = 'none';
    
    // Limpiar el formulario
    form.reset();
}

// Cerrar modal al hacer click fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('editParticipanteModal');
    if (event.target === modal) {
        closeEditModal();
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar display
    updateDisplay();
    
    // Vincular evento de filtrado
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        console.log('Event listener agregado al input de búsqueda');
        searchInput.addEventListener('input', function() {
            console.log('Evento input disparado');
            filterTable();
        });
        
        // Limpiar filtro al presionar Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                filterTable();
                searchInput.blur(); // Opcional: quitar el foco del input
            }
        });
    } else {
        console.error('No se encontró el input searchInput');
    }
});
</script>
