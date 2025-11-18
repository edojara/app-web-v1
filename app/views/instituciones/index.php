<div class="container">
    <div class="card">
        <div class="page-header">
            <h1>🏛️ Instituciones Académicas</h1>
        </div>

        <!-- Barra de búsqueda y acciones -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">
            <input type="text" 
                   id="searchInput" 
                   placeholder="🔍 Buscar por nombre, ciudad o dirección..." 
                   style="flex: 0 0 50%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
            <a href="<?php echo APP_URL; ?>/?url=instituciones/create" class="btn btn-success">➕ Nueva Institución</a>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alerta alerta-exito">
                <?php 
                echo $_SESSION['mensaje']; 
                unset($_SESSION['mensaje']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alerta alerta-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($instituciones)): ?>
            <p style="text-align: center; color: #666; padding: 2rem;">
                No hay instituciones registradas. 
                <a href="<?php echo APP_URL; ?>/?url=instituciones/create">Crear la primera institución</a>
            </p>
        <?php else: ?>
            <style>
                .instituciones-grid {
                    display: grid;
                    grid-template-columns: 
                        minmax(40px, 60px)      /* # */
                        minmax(250px, 3fr)      /* Nombre */
                        minmax(120px, 1.2fr)    /* Ciudad */
                        minmax(200px, 2fr)      /* Dirección */
                        minmax(100px, 0.8fr)    /* Contactos */
                        minmax(120px, 0.9fr)    /* Participantes */
                        minmax(80px, 0.7fr)     /* Estado */
                        minmax(120px, 0.9fr);   /* Acciones */
                    gap: 0;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    overflow: hidden;
                    background: white;
                    width: 100%;
                }
                .instituciones-grid-header {
                    display: contents;
                }
                .instituciones-grid-header > div {
                    background: var(--primary-color);
                    color: white;
                    padding: 0.75rem 0.5rem;
                    font-weight: 600;
                    font-size: 0.9rem;
                    border-bottom: 2px solid #fff;
                }
                .instituciones-grid-header .sortable:hover {
                    background: #1565c0;
                }
                .instituciones-grid-row {
                    display: contents;
                    cursor: pointer;
                }
                .instituciones-grid-row > div {
                    padding: 0.75rem 0.5rem;
                    border-bottom: 1px solid #f0f0f0;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    word-wrap: break-word;
                    line-height: 1.4;
                }
                .instituciones-grid-row:hover > div {
                    background-color: #e3f2fd;
                }
                .text-center {
                    text-align: center;
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

                    .instituciones-grid {
                        display: block;
                        border: none;
                    }
                    
                    .instituciones-grid-header {
                        display: none;
                    }
                    
                    .instituciones-grid-row {
                        display: block;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        margin-bottom: 1rem;
                        padding: 1rem;
                        background: white;
                    }
                    
                    .instituciones-grid-row > div {
                        display: block;
                        padding: 0.5rem 0;
                        border-bottom: none;
                        white-space: normal;
                        overflow: visible;
                    }
                    
                    .instituciones-grid-row > div:before {
                        content: attr(data-label);
                        font-weight: bold;
                        display: inline-block;
                        width: 100px;
                        color: var(--primary-color);
                    }
                    
                    .instituciones-grid-row > div:first-child {
                        font-size: 1.2rem;
                        font-weight: bold;
                        color: var(--primary-color);
                        text-align: center;
                        padding-bottom: 0.5rem;
                        border-bottom: 2px solid #e0e0e0;
                        margin-bottom: 0.5rem;
                    }
                    
                    .instituciones-grid-row > div:first-child:before {
                        content: '#';
                        margin-right: 0.5rem;
                    }
                    
                    .instituciones-grid-row > div:last-child {
                        text-align: center;
                        padding-top: 1rem;
                        border-top: 2px solid #e0e0e0;
                        margin-top: 0.5rem;
                    }
                    
                    .instituciones-grid-row > div:last-child:before {
                        display: none;
                    }
                }
            </style>

            <div class="instituciones-grid">
                <!-- Header -->
                <div class="instituciones-grid-header">
                    <div>#</div>
                    <div class="sortable" onclick="sortTable('nombre')" style="cursor: pointer;" title="Click para ordenar">
                        Nombre <span id="sort-nombre">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('ciudad')" style="cursor: pointer;" title="Click para ordenar">
                        Ciudad <span id="sort-ciudad">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('direccion')" style="cursor: pointer;" title="Click para ordenar">
                        Dirección <span id="sort-direccion">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('contactos')" style="cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.25rem;" title="Click para ordenar">
                        Contactos <span id="sort-contactos">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('participantes')" style="cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.25rem;" title="Click para ordenar">
                        Participantes <span id="sort-participantes">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('estado')" style="cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.25rem;" title="Click para ordenar">
                        Estado <span id="sort-estado">↕️</span>
                    </div>
                    <div class="text-center">Acciones</div>
                </div>
                
                <!-- Rows -->
                <?php 
                $contador = 1;
                foreach ($instituciones as $inst): 
                ?>
                <div class="instituciones-grid-row" 
                     ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=instituciones/view&id=<?php echo $inst['id']; ?>'" 
                     title="Doble click para ver detalles"
                     data-nombre="<?php echo htmlspecialchars($inst['nombre']); ?>"
                     data-ciudad="<?php echo htmlspecialchars($inst['ciudad']); ?>"
                     data-direccion="<?php echo htmlspecialchars($inst['direccion']); ?>"
                     data-contactos="<?php echo $inst['total_contactos']; ?>"
                     data-participantes="<?php echo $inst['total_participantes']; ?>"
                     data-estado="<?php echo $inst['estado']; ?>">
                    <div class="row-numero"><?php echo $contador++; ?></div>
                    <div data-label="Nombre: " title="<?php echo htmlspecialchars($inst['nombre']); ?>"><strong><?php echo htmlspecialchars($inst['nombre']); ?></strong></div>
                    <div data-label="Ciudad: " title="<?php echo htmlspecialchars($inst['ciudad']); ?>"><?php echo htmlspecialchars($inst['ciudad']); ?></div>
                    <div data-label="Dirección: " title="<?php echo htmlspecialchars($inst['direccion']); ?>"><?php echo htmlspecialchars($inst['direccion']); ?></div>
                    <div data-label="Contactos: " class="text-center">
                        <?php echo $inst['total_contactos']; ?>
                    </div>
                    <div data-label="Participantes: " class="text-center">
                        <?php echo $inst['total_participantes']; ?>
                    </div>
                    <div data-label="Estado: ">
                        <?php if ($inst['estado'] === 'activa'): ?>
                            <span class="badge badge-success">✓ Activa</span>
                        <?php else: ?>
                            <span class="badge badge-warning">✗ Inactiva</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-center" style="white-space: normal;">
                        <a href="<?php echo APP_URL; ?>/?url=instituciones/view&id=<?php echo $inst['id']; ?>" 
                           class="btn-action btn-view" 
                           title="Ver detalles">👁️</a>
                        <button onclick="editInstitucion(<?php echo $inst['id']; ?>, '<?php echo htmlspecialchars($inst['nombre'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($inst['ciudad'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($inst['direccion'], ENT_QUOTES); ?>', '<?php echo $inst['estado']; ?>')" 
                           class="btn-action btn-edit" 
                           title="Editar">✏️</button>
                        <a href="<?php echo APP_URL; ?>/?url=instituciones/delete&id=<?php echo $inst['id']; ?>" 
                           class="btn-action btn-delete" 
                           title="Eliminar"
                           onclick="return confirm('¿Estás seguro de eliminar esta institución? Se eliminarán también todos sus contactos.');">🗑️</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Control de paginación -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding: 1rem; background-color: #f8f9fa; border-radius: 4px;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="recordsPerPage" style="font-weight: 500;">Mostrar:</label>
                    <select id="recordsPerPage" onchange="updateRecordsPerPage()" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                        <option value="10">10 registros</option>
                        <option value="20" selected>20 registros</option>
                        <option value="50">50 registros</option>
                        <option value="100">100 registros</option>
                        <option value="999999">Todos</option>
                    </select>
                </div>
                <div id="paginationControls" style="display: flex; align-items: center; gap: 0.5rem;">
                    <!-- Pagination buttons will be inserted here -->
                </div>
                <div id="recordsInfo" style="color: #666; font-size: 0.9rem;">
                    Mostrando <span id="showingStart">0</span>-<span id="showingEnd">0</span> de <span id="totalCount">0</span> instituciones
                </div>
            </div>

            <script>
            let sortOrders = {
                nombre: 'asc',
                ciudad: 'asc',
                direccion: 'asc',
                contactos: 'asc',
                participantes: 'asc',
                estado: 'asc'
            };
            
            let currentPage = 1;
            let recordsPerPage = 20;

            // Initialize on load
            window.addEventListener('DOMContentLoaded', function() {
                updateDisplay();
                
                // Vincular evento de filtrado
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        filterTable();
                    });
                    
                    // Limpiar filtro al presionar Escape
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            searchInput.value = '';
                            filterTable();
                            searchInput.blur();
                        }
                    });
                }
            });

            function sortTable(column) {
                const grid = document.querySelector('.instituciones-grid');
                const rows = Array.from(document.querySelectorAll('.instituciones-grid-row'));
                
                // Toggle sort order
                sortOrders[column] = sortOrders[column] === 'asc' ? 'desc' : 'asc';
                const order = sortOrders[column];
                
                // Sort rows
                rows.sort((a, b) => {
                    let aVal = a.getAttribute('data-' + column);
                    let bVal = b.getAttribute('data-' + column);
                    
                    // Convert to numbers if it's contactos or participantes
                    if (column === 'contactos' || column === 'participantes') {
                        aVal = parseInt(aVal);
                        bVal = parseInt(bVal);
                    } else {
                        aVal = aVal.toLowerCase();
                        bVal = bVal.toLowerCase();
                    }
                    
                    if (order === 'asc') {
                        return aVal > bVal ? 1 : -1;
                    } else {
                        return aVal < bVal ? 1 : -1;
                    }
                });
                
                // Remove existing rows
                rows.forEach(row => row.remove());
                
                // Re-append sorted rows
                rows.forEach(row => grid.appendChild(row));
                
                // Update row numbers
                const numeroElements = document.querySelectorAll('.row-numero');
                numeroElements.forEach((el, index) => {
                    el.textContent = index + 1;
                });
                
                // Update sort indicators
                Object.keys(sortOrders).forEach(col => {
                    const indicator = document.getElementById('sort-' + col);
                    if (indicator) {
                        if (col === column) {
                            indicator.textContent = order === 'asc' ? '🔼' : '🔽';
                        } else {
                            indicator.textContent = '↕️';
                        }
                    }
                });
                
                // Update display after sorting
                updateDisplay();
            }
            
            function filterTable() {
                const searchValue = document.getElementById('searchInput').value.toLowerCase();
                const rows = document.querySelectorAll('.instituciones-grid-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const nombre = row.getAttribute('data-nombre').toLowerCase();
                    const ciudad = row.getAttribute('data-ciudad').toLowerCase();
                    const direccion = row.getAttribute('data-direccion').toLowerCase();
                    
                    const matches = nombre.includes(searchValue) || 
                                  ciudad.includes(searchValue) || 
                                  direccion.includes(searchValue);
                    
                    if (matches) {
                        row.style.display = 'contents';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update row numbers for visible rows
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                visibleRows.forEach((row, index) => {
                    const numeroEl = row.querySelector('.row-numero');
                    if (numeroEl) {
                        numeroEl.textContent = index + 1;
                    }
                });
                
                // Show message if no results
                let noResultsMsg = document.getElementById('noResultsMsg');
                if (visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMsg';
                        noResultsMsg.style.gridColumn = '1 / -1';
                        noResultsMsg.style.padding = '2rem';
                        noResultsMsg.style.textAlign = 'center';
                        noResultsMsg.style.color = '#666';
                        noResultsMsg.innerHTML = 'No se encontraron instituciones que coincidan con la búsqueda';
                        document.querySelector('.instituciones-grid').appendChild(noResultsMsg);
                    }
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                }
                
                // Update display after filtering
                updateDisplay();
            }
            
            function updateRecordsPerPage() {
                recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);
                currentPage = 1;
                updateDisplay();
            }
            
            function updateDisplay() {
                const rows = Array.from(document.querySelectorAll('.instituciones-grid-row'));
                
                // First, get all rows (regardless of current display)
                const allRows = rows.filter(row => {
                    // Check if row matches current filter
                    const searchValue = document.getElementById('searchInput').value.toLowerCase();
                    if (searchValue) {
                        const nombre = row.getAttribute('data-nombre').toLowerCase();
                        const ciudad = row.getAttribute('data-ciudad').toLowerCase();
                        const direccion = row.getAttribute('data-direccion').toLowerCase();
                        return nombre.includes(searchValue) || ciudad.includes(searchValue) || direccion.includes(searchValue);
                    }
                    return true;
                });
                
                const totalRecords = allRows.length;
                
                // Calculate total pages
                const totalPages = Math.ceil(totalRecords / recordsPerPage);
                
                // Ensure current page is valid
                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = totalPages;
                }
                if (currentPage < 1) {
                    currentPage = 1;
                }
                
                // Hide all rows first
                rows.forEach(row => row.style.display = 'none');
                
                // Calculate pagination
                const startIndex = (currentPage - 1) * recordsPerPage;
                const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);
                
                // Show only records for current page
                for (let i = startIndex; i < endIndex; i++) {
                    if (allRows[i]) {
                        allRows[i].style.display = 'contents';
                    }
                }
                
                // Update row numbers for visible rows
                const displayedRows = allRows.slice(startIndex, endIndex);
                displayedRows.forEach((row, index) => {
                    const numeroEl = row.querySelector('.row-numero');
                    if (numeroEl) {
                        numeroEl.textContent = startIndex + index + 1;
                    }
                });
                
                // Update info text
                document.getElementById('showingStart').textContent = totalRecords > 0 ? startIndex + 1 : 0;
                document.getElementById('showingEnd').textContent = endIndex;
                document.getElementById('totalCount').textContent = totalRecords;
                
                // Update pagination controls
                updatePaginationControls(totalPages);
            }
            
            function updatePaginationControls(totalPages) {
                const paginationDiv = document.getElementById('paginationControls');
                
                if (totalPages <= 1) {
                    paginationDiv.innerHTML = '';
                    return;
                }
                
                let html = '';
                
                // Previous button
                html += `<button onclick="goToPage(${currentPage - 1})" 
                         ${currentPage === 1 ? 'disabled' : ''} 
                         style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; ${currentPage === 1 ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                         ← Anterior
                         </button>`;
                
                // Page numbers
                const maxButtons = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(totalPages, startPage + maxButtons - 1);
                
                if (endPage - startPage < maxButtons - 1) {
                    startPage = Math.max(1, endPage - maxButtons + 1);
                }
                
                if (startPage > 1) {
                    html += `<button onclick="goToPage(1)" style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">1</button>`;
                    if (startPage > 2) {
                        html += `<span style="padding: 0.4rem;">...</span>`;
                    }
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    const isActive = i === currentPage;
                    html += `<button onclick="goToPage(${i})" 
                             style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: ${isActive ? 'var(--primary-color)' : 'white'}; color: ${isActive ? 'white' : '#333'}; border-radius: 4px; cursor: pointer; font-weight: ${isActive ? '600' : '400'};">
                             ${i}
                             </button>`;
                }
                
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        html += `<span style="padding: 0.4rem;">...</span>`;
                    }
                    html += `<button onclick="goToPage(${totalPages})" style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">${totalPages}</button>`;
                }
                
                // Next button
                html += `<button onclick="goToPage(${currentPage + 1})" 
                         ${currentPage === totalPages ? 'disabled' : ''} 
                         style="padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer; ${currentPage === totalPages ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                         Siguiente →
                         </button>`;
                
                paginationDiv.innerHTML = html;
            }
            
            function goToPage(page) {
                currentPage = page;
                updateDisplay();
            }
            </script>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para editar institución -->
<div id="editInstitucionModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 50px auto; padding: 0; border-radius: 8px; width: 90%; max-width: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 2px solid #e0e0e0; background-color: #2196f3; color: white; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0; font-size: 1.5rem;">✏️ Editar Institución</h2>
            <button onclick="closeEditModal()" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: white; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <form id="editInstitucionForm" method="POST">
                <input type="hidden" id="edit_institucion_id" name="institucion_id">
                
                <div style="margin-bottom: 1rem;">
                    <label for="edit_nombre" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Nombre <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" 
                           id="edit_nombre" 
                           name="nombre" 
                           class="form-control" 
                           required
                           maxlength="255"
                           style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                </div>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label for="edit_ciudad" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Ciudad <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" 
                               id="edit_ciudad" 
                               name="ciudad" 
                               class="form-control" 
                               required
                               maxlength="100"
                               style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_estado" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Estado <span style="color: #dc3545;">*</span>
                        </label>
                        <select id="edit_estado" 
                                name="estado" 
                                class="form-control" 
                                required
                                style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                            <option value="activa">Activa</option>
                            <option value="inactiva">Inactiva</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit_direccion" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Dirección <span style="color: #dc3545;">*</span>
                    </label>
                    <textarea id="edit_direccion" 
                              name="direccion" 
                              class="form-control" 
                              required
                              rows="3"
                              maxlength="500"
                              style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary" style="padding: 0.625rem 1.25rem; border: none; border-radius: 4px; cursor: pointer; background-color: #6c757d; color: white;">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.625rem 1.25rem; border: none; border-radius: 4px; cursor: pointer; background-color: #2196f3; color: white;">💾 Actualizar Institución</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editInstitucion(id, nombre, ciudad, direccion, estado) {
    // Abrir el modal
    const modal = document.getElementById('editInstitucionModal');
    const form = document.getElementById('editInstitucionForm');
    
    // Configurar el action del formulario
    form.action = '<?php echo APP_URL; ?>/?url=instituciones/update&id=' + id;
    
    // Rellenar los campos con los datos actuales
    document.getElementById('edit_institucion_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_ciudad').value = ciudad;
    document.getElementById('edit_direccion').value = direccion;
    document.getElementById('edit_estado').value = estado;
    
    // Mostrar el modal
    modal.style.display = 'block';
}

function closeEditModal() {
    const modal = document.getElementById('editInstitucionModal');
    const form = document.getElementById('editInstitucionForm');
    
    // Ocultar el modal
    modal.style.display = 'none';
    
    // Limpiar el formulario
    form.reset();
}

// Cerrar modal al hacer click fuera de él
window.addEventListener('click', function(event) {
    const modal = document.getElementById('editInstitucionModal');
    if (event.target === modal) {
        closeEditModal();
    }
});
</script>
