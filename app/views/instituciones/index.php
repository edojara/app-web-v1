<div class="container">
    <div class="card">
        <div class="page-header">
            <h1>🏛️ Instituciones Académicas</h1>
            <div class="page-actions">
                <a href="<?php echo APP_URL; ?>/?url=instituciones/create" class="btn btn-success">➕ Nueva Institución</a>
            </div>
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
                        minmax(200px, 2fr)      /* Nombre */
                        minmax(120px, 1fr)      /* Ciudad */
                        minmax(180px, 1.5fr)    /* Dirección */
                        minmax(100px, 0.7fr)    /* Contactos */
                        minmax(80px, 0.6fr)     /* Estado */
                        minmax(120px, 0.8fr);   /* Acciones */
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
                    white-space: nowrap;
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
                    <div class="sortable text-center" onclick="sortTable('contactos')" style="cursor: pointer;" title="Click para ordenar">
                        Contactos <span id="sort-contactos">↕️</span>
                    </div>
                    <div class="sortable" onclick="sortTable('estado')" style="cursor: pointer;" title="Click para ordenar">
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
                     data-estado="<?php echo $inst['estado']; ?>">
                    <div class="row-numero"><?php echo $contador++; ?></div>
                    <div title="<?php echo htmlspecialchars($inst['nombre']); ?>"><strong><?php echo htmlspecialchars($inst['nombre']); ?></strong></div>
                    <div title="<?php echo htmlspecialchars($inst['ciudad']); ?>"><?php echo htmlspecialchars($inst['ciudad']); ?></div>
                    <div title="<?php echo htmlspecialchars($inst['direccion']); ?>"><?php echo htmlspecialchars($inst['direccion']); ?></div>
                    <div class="text-center">
                        <span class="badge badge-primary"><?php echo $inst['total_contactos']; ?></span>
                    </div>
                    <div>
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
                        <a href="<?php echo APP_URL; ?>/?url=instituciones/edit&id=<?php echo $inst['id']; ?>" 
                           class="btn-action btn-edit" 
                           title="Editar">✏️</a>
                        <a href="<?php echo APP_URL; ?>/?url=instituciones/delete&id=<?php echo $inst['id']; ?>" 
                           class="btn-action btn-delete" 
                           title="Eliminar"
                           onclick="return confirm('¿Estás seguro de eliminar esta institución? Se eliminarán también todos sus contactos.');">🗑️</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <script>
            let sortOrders = {
                nombre: 'asc',
                ciudad: 'asc',
                direccion: 'asc',
                contactos: 'asc',
                estado: 'asc'
            };

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
                    
                    // Convert to numbers if it's contactos
                    if (column === 'contactos') {
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
            }
            </script>
        <?php endif; ?>
    </div>
</div>
