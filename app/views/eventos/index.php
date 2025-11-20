<div class="container-fluid" style="max-width: 98%; padding: 0 1rem;">
    <div class="content-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; margin-top: 1.5rem;">
        <button onclick="openCreateModal()" class="btn btn-primary" style="white-space: nowrap;">
            ➕ Nuevo Evento
        </button>
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
            <?php if (empty($eventos)): ?>
                <h2 style="margin-bottom: 1.5rem;">📅 Eventos</h2>
                <div class="text-center py-4">
                    <p class="text-muted">No hay eventos registrados</p>
                    <button onclick="openCreateModal()" class="btn btn-primary">Crear primer evento</button>
                </div>
            <?php else: ?>
                <!-- Eventos Próximos -->
                <?php if (!empty($eventosProximos)): ?>
                    <h2 style="margin-bottom: 1rem; color: #2196f3;">🔔 EVENTOS PRÓXIMOS</h2>
                    <input 
                        type="text" 
                        id="searchProximos" 
                        class="form-control" 
                        placeholder="Buscar en eventos próximos..." 
                        style="margin-bottom: 1rem; max-width: 400px;">
                    <div style="margin-bottom: 3rem;">
                        <!-- Desktop: Grid -->
                        <div class="desktop-table">
                            <div class="grid-header" style="display: grid; grid-template-columns: 50px 3fr 1.5fr 1.5fr 0.8fr 2fr 200px; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; font-weight: 600; border-bottom: 2px solid #dee2e6;">
                                <div>#</div>
                                <div>Nombre</div>
                                <div>Fecha Inicio</div>
                                <div>Fecha Término</div>
                                <div style="text-align: center;">Días</div>
                                <div>Lugar</div>
                                <div style="text-align: center;">Acciones</div>
                            </div>
                            <?php $numero = 1; ?>
                            <?php foreach ($eventosProximos as $evento): 
                                $inicio = new DateTime($evento['fecha_inicio']);
                                $termino = new DateTime($evento['fecha_termino']);
                                $dias = $inicio->diff($termino)->days + 1; // +1 para incluir ambos días
                            ?>
                                <div class="grid-row" ondblclick="window.location.href='/?url=eventos/view&id=<?= $evento['id'] ?>'" style="display: grid; grid-template-columns: 50px 3fr 1.5fr 1.5fr 0.8fr 2fr 200px; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #dee2e6; cursor: pointer; align-items: center;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                                    <div><?= $numero++ ?></div>
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong><?= htmlspecialchars($evento['nombre']) ?></strong></div>
                                    <div><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?></div>
                                    <div><?= date('d/m/Y', strtotime($evento['fecha_termino'])) ?></div>
                                    <div style="text-align: center;"><?= $dias ?></div>
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($evento['lugar']) ?></div>
                                    <div onclick="event.stopPropagation();" ondblclick="event.stopPropagation();" style="display: flex; gap: 5px; justify-content: center;">
                                        <button onclick="event.stopPropagation(); window.location.href='/?url=checkin&evento_id=<?= $evento['id'] ?>';" 
                                           style="background: transparent; color: #9c27b0; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#7b1fa2'" 
                                           onmouseout="this.style.color='#9c27b0'"
                                           title="Check-in">✅</button>
                                        <button onclick="event.stopPropagation(); window.location.href='/?url=inscripciones&evento_id=<?= $evento['id'] ?>';" 
                                           style="background: transparent; color: #4caf50; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#388e3c'" 
                                           onmouseout="this.style.color='#4caf50'"
                                           title="Inscripciones">👥</button>
                                        <button onclick="event.stopPropagation(); if(confirm('¿Está seguro de eliminar este evento?')) window.location.href='/?url=eventos/delete&id=<?= $evento['id'] ?>';\" 
                                           style="background: transparent; color: #f44336; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#d32f2f'" 
                                           onmouseout="this.style.color='#f44336'"
                                           title="Eliminar">🗑️</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Controles de paginación -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                            <div>
                                <label for="recordsPerPageProximos">Mostrar: </label>
                                <select id="recordsPerPageProximos" onchange="changeRecordsPerPageProximos()" class="form-control" style="display: inline-block; width: auto;">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="all">Todos</option>
                                </select>
                                <span id="recordsInfoProximos" style="margin-left: 1rem;"></span>
                            </div>
                            <div id="paginationButtonsProximos"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Eventos Pasados -->
                <?php if (!empty($eventosPasados)): ?>
                    <h2 style="margin-bottom: 1rem; color: #757575;">📋 EVENTOS REALIZADOS</h2>
                    <input 
                        type="text" 
                        id="searchPasados" 
                        class="form-control" 
                        placeholder="Buscar en eventos realizados..." 
                        style="margin-bottom: 1rem; max-width: 400px;">
                    <div>
                        <!-- Desktop: Grid -->
                        <div class="desktop-table">
                            <div class="grid-header" style="display: grid; grid-template-columns: 50px 3fr 1.5fr 1.5fr 0.8fr 2fr 200px; gap: 0.75rem; padding: 0.75rem; background: #f8f9fa; font-weight: 600; border-bottom: 2px solid #dee2e6;">
                                <div>#</div>
                                <div>Nombre</div>
                                <div>Fecha Inicio</div>
                                <div>Fecha Término</div>
                                <div style="text-align: center;">Días</div>
                                <div>Lugar</div>
                                <div style="text-align: center;">Acciones</div>
                            </div>
                            <?php $numero = 1; ?>
                            <?php foreach ($eventosProximos as $evento): 
                                $inicio = new DateTime($evento['fecha_inicio']);
                                $termino = new DateTime($evento['fecha_termino']);
                                $dias = $inicio->diff($termino)->days + 1; // +1 para incluir ambos días
                            ?>
                                <div class="grid-row" ondblclick="window.location.href='/?url=eventos/view&id=<?= $evento['id'] ?>'" style="display: grid; grid-template-columns: 50px 3fr 1.5fr 1.5fr 0.8fr 2fr 200px; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #dee2e6; cursor: pointer; align-items: center; opacity: 0.7;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                                    <div><?= $numero++ ?></div>
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong><?= htmlspecialchars($evento['nombre']) ?></strong></div>
                                    <div><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?></div>
                                    <div><?= date('d/m/Y', strtotime($evento['fecha_termino'])) ?></div>
                                    <div style="text-align: center;"><?= $dias ?></div>
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($evento['lugar']) ?></div>
                                    <div onclick="event.stopPropagation();" ondblclick="event.stopPropagation();" style="display: flex; gap: 5px; justify-content: center;">
                                        <button onclick="event.stopPropagation(); window.location.href='/?url=checkin&evento_id=<?= $evento['id'] ?>';" 
                                           style="background: transparent; color: #9c27b0; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#7b1fa2'" 
                                           onmouseout="this.style.color='#9c27b0'"
                                           title="Check-in">✅</button>
                                        <button onclick="event.stopPropagation(); window.location.href='/?url=inscripciones&evento_id=<?= $evento['id'] ?>';" 
                                           style="background: transparent; color: #4caf50; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#388e3c'" 
                                           onmouseout="this.style.color='#4caf50'"
                                           title="Inscripciones">👥</button>
                                        <button onclick="event.stopPropagation(); if(confirm('¿Está seguro de eliminar este evento?')) window.location.href='/?url=eventos/delete&id=<?= $evento['id'] ?>';\" 
                                           style="background: transparent; color: #f44336; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                           onmouseover="this.style.color='#d32f2f'" 
                                           onmouseout="this.style.color='#f44336'"
                                           title="Eliminar">🗑️</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Controles de paginación -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                            <div>
                                <label for="recordsPerPagePasados">Mostrar: </label>
                                <select id="recordsPerPagePasados" onchange="changeRecordsPerPagePasados()" class="form-control" style="display: inline-block; width: auto;">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="all">Todos</option>
                                </select>
                                <span id="recordsInfoPasados" style="margin-left: 1rem;"></span>
                            </div>
                            <div id="paginationButtonsPasados"></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para crear evento -->
<div id="createEventoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 50px auto; padding: 0; border-radius: 8px; width: 90%; max-width: 700px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 2px solid #e0e0e0; background-color: #2196f3; color: white; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0; font-size: 1.5rem;">📅 Nuevo Evento</h2>
            <button onclick="closeCreateModal()" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: white; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <form id="createEventoForm" method="POST" action="/?url=eventos/store">
                <div style="margin-bottom: 1rem;">
                    <label for="create_nombre" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Nombre del Evento <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" id="create_nombre" name="nombre" class="form-control" required maxlength="255">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="create_descripcion" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Descripción
                    </label>
                    <textarea id="create_descripcion" name="descripcion" class="form-control" rows="3" maxlength="1000"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label for="create_fecha_inicio" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha de Inicio <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="date" id="create_fecha_inicio" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="create_fecha_termino" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha de Término <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="date" id="create_fecha_termino" name="fecha_termino" class="form-control" required>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="create_lugar" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Lugar <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" id="create_lugar" name="lugar" class="form-control" required maxlength="255">
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
                    <button type="button" onclick="closeCreateModal()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">💾 Crear Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar evento -->
<div id="editEventoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 50px auto; padding: 0; border-radius: 8px; width: 90%; max-width: 700px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 2px solid #e0e0e0; background-color: #2196f3; color: white; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0; font-size: 1.5rem;">✏️ Editar Evento</h2>
            <button onclick="closeEditModal()" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: white; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <form id="editEventoForm" method="POST" action="/?url=eventos/update">
                <input type="hidden" id="edit_evento_id" name="id">

                <div style="margin-bottom: 1rem;">
                    <label for="edit_nombre" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Nombre del Evento <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" id="edit_nombre" name="nombre" class="form-control" required maxlength="255">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit_descripcion" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Descripción
                    </label>
                    <textarea id="edit_descripcion" name="descripcion" class="form-control" rows="3" maxlength="1000"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label for="edit_fecha_inicio" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha de Inicio <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="date" id="edit_fecha_inicio" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_fecha_termino" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha de Término <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="date" id="edit_fecha_termino" name="fecha_termino" class="form-control" required>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit_lugar" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Lugar <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="text" id="edit_lugar" name="lugar" class="form-control" required maxlength="255">
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">💾 Actualizar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Abrir modal de crear
function openCreateModal() {
    document.getElementById('createEventoForm').reset();
    document.getElementById('createEventoModal').style.display = 'block';
}

// Cerrar modal de crear
function closeCreateModal() {
    document.getElementById('createEventoModal').style.display = 'none';
}

// Abrir modal de editar
function editEvento(id, nombre, descripcion, fechaInicio, fechaTermino, lugar) {
    document.getElementById('edit_evento_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('edit_fecha_inicio').value = fechaInicio.replace(' ', 'T');
    document.getElementById('edit_fecha_termino').value = fechaTermino.replace(' ', 'T');
    document.getElementById('edit_lugar').value = lugar;
    document.getElementById('editEventoModal').style.display = 'block';
}

// Cerrar modal de editar
function closeEditModal() {
    document.getElementById('editEventoModal').style.display = 'none';
}

// Cerrar modales al hacer clic fuera
window.onclick = function(event) {
    const createModal = document.getElementById('createEventoModal');
    const editModal = document.getElementById('editEventoModal');
    if (event.target == createModal) {
        closeCreateModal();
    }
    if (event.target == editModal) {
        closeEditModal();
    }
}

// Validación de fechas en formulario de crear
document.getElementById('createEventoForm').addEventListener('submit', function(e) {
    const inicio = document.getElementById('create_fecha_inicio').value;
    const termino = document.getElementById('create_fecha_termino').value;
    
    if (inicio && termino && new Date(termino) < new Date(inicio)) {
        e.preventDefault();
        alert('La fecha de término debe ser posterior a la fecha de inicio');
        return false;
    }
});

// Validación de fechas en formulario de editar
document.getElementById('editEventoForm').addEventListener('submit', function(e) {
    const inicio = document.getElementById('edit_fecha_inicio').value;
    const termino = document.getElementById('edit_fecha_termino').value;
    
    if (inicio && termino && new Date(termino) < new Date(inicio)) {
        e.preventDefault();
        alert('La fecha de término debe ser posterior a la fecha de inicio');
        return false;
    }
});

// Filtrado independiente para eventos próximos
const searchProximos = document.getElementById('searchProximos');
if (searchProximos) {
    searchProximos.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tables = document.querySelectorAll('.desktop-table');
        
        // La primera tabla son los eventos próximos
        if (tables.length > 0) {
            const rows = tables[0].querySelectorAll('.grid-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? 'grid' : 'none';
            });
        }
    });
    
    searchProximos.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            const tables = document.querySelectorAll('.desktop-table');
            if (tables.length > 0) {
                const rows = tables[0].querySelectorAll('.grid-row');
                rows.forEach(row => row.style.display = 'grid');
            }
            this.blur();
        }
    });
}

// Filtrado independiente para eventos pasados
const searchPasados = document.getElementById('searchPasados');
if (searchPasados) {
    searchPasados.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tables = document.querySelectorAll('.desktop-table');
        
        // La segunda tabla son los eventos pasados
        if (tables.length > 1) {
            const rows = tables[1].querySelectorAll('.grid-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? 'grid' : 'none';
            });
        }
    });
    
    searchPasados.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            const tables = document.querySelectorAll('.desktop-table');
            if (tables.length > 1) {
                const rows = tables[1].querySelectorAll('.grid-row');
                rows.forEach(row => row.style.display = 'grid');
            }
            this.blur();
        }
    });
}

// ========== PAGINACIÓN PARA EVENTOS PRÓXIMOS ==========
let currentPageProximos = 1;
let recordsPerPageProximos = 10;

function updateDisplayProximos() {
    const tables = document.querySelectorAll('.desktop-table');
    if (tables.length === 0) return;
    
    const rows = Array.from(tables[0].querySelectorAll('.grid-row'));
    const totalRecords = rows.length;
    
    // Calcular paginación
    const totalPages = recordsPerPageProximos === 'all' ? 1 : Math.ceil(totalRecords / recordsPerPageProximos);
    const startIndex = recordsPerPageProximos === 'all' ? 0 : (currentPageProximos - 1) * recordsPerPageProximos;
    const endIndex = recordsPerPageProximos === 'all' ? totalRecords : startIndex + recordsPerPageProximos;
    
    // Mostrar/ocultar filas
    rows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = 'grid';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Actualizar info
    const recordsInfo = document.getElementById('recordsInfoProximos');
    if (recordsInfo) {
        recordsInfo.textContent = `Mostrando ${startIndex + 1}-${Math.min(endIndex, totalRecords)} de ${totalRecords} eventos`;
    }
    
    // Actualizar botones de paginación
    const paginationButtons = document.getElementById('paginationButtonsProximos');
    if (paginationButtons && recordsPerPageProximos !== 'all') {
        paginationButtons.innerHTML = '';
        
        // Botón anterior
        if (currentPageProximos > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.textContent = '← Anterior';
            prevBtn.className = 'btn btn-sm btn-secondary';
            prevBtn.style.marginRight = '0.5rem';
            prevBtn.onclick = () => goToPageProximos(currentPageProximos - 1);
            paginationButtons.appendChild(prevBtn);
        }
        
        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.textContent = i;
            pageBtn.className = i === currentPageProximos ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
            pageBtn.style.marginRight = '0.25rem';
            pageBtn.onclick = () => goToPageProximos(i);
            paginationButtons.appendChild(pageBtn);
        }
        
        // Botón siguiente
        if (currentPageProximos < totalPages) {
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Siguiente →';
            nextBtn.className = 'btn btn-sm btn-secondary';
            nextBtn.style.marginLeft = '0.25rem';
            nextBtn.onclick = () => goToPageProximos(currentPageProximos + 1);
            paginationButtons.appendChild(nextBtn);
        }
    } else if (paginationButtons) {
        paginationButtons.innerHTML = '';
    }
}

function goToPageProximos(page) {
    currentPageProximos = page;
    updateDisplayProximos();
}

function changeRecordsPerPageProximos() {
    const select = document.getElementById('recordsPerPageProximos');
    recordsPerPageProximos = select.value === 'all' ? 'all' : parseInt(select.value);
    currentPageProximos = 1;
    updateDisplayProximos();
}

// ========== PAGINACIÓN PARA EVENTOS PASADOS ==========
let currentPagePasados = 1;
let recordsPerPagePasados = 10;

function updateDisplayPasados() {
    const tables = document.querySelectorAll('.desktop-table');
    if (tables.length < 2) return;
    
    const rows = Array.from(tables[1].querySelectorAll('.grid-row'));
    const totalRecords = rows.length;
    
    // Calcular paginación
    const totalPages = recordsPerPagePasados === 'all' ? 1 : Math.ceil(totalRecords / recordsPerPagePasados);
    const startIndex = recordsPerPagePasados === 'all' ? 0 : (currentPagePasados - 1) * recordsPerPagePasados;
    const endIndex = recordsPerPagePasados === 'all' ? totalRecords : startIndex + recordsPerPagePasados;
    
    // Mostrar/ocultar filas
    rows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = 'grid';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Actualizar info
    const recordsInfo = document.getElementById('recordsInfoPasados');
    if (recordsInfo) {
        recordsInfo.textContent = `Mostrando ${startIndex + 1}-${Math.min(endIndex, totalRecords)} de ${totalRecords} eventos`;
    }
    
    // Actualizar botones de paginación
    const paginationButtons = document.getElementById('paginationButtonsPasados');
    if (paginationButtons && recordsPerPagePasados !== 'all') {
        paginationButtons.innerHTML = '';
        
        // Botón anterior
        if (currentPagePasados > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.textContent = '← Anterior';
            prevBtn.className = 'btn btn-sm btn-secondary';
            prevBtn.style.marginRight = '0.5rem';
            prevBtn.onclick = () => goToPagePasados(currentPagePasados - 1);
            paginationButtons.appendChild(prevBtn);
        }
        
        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.textContent = i;
            pageBtn.className = i === currentPagePasados ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
            pageBtn.style.marginRight = '0.25rem';
            pageBtn.onclick = () => goToPagePasados(i);
            paginationButtons.appendChild(pageBtn);
        }
        
        // Botón siguiente
        if (currentPagePasados < totalPages) {
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Siguiente →';
            nextBtn.className = 'btn btn-sm btn-secondary';
            nextBtn.style.marginLeft = '0.25rem';
            nextBtn.onclick = () => goToPagePasados(currentPagePasados + 1);
            paginationButtons.appendChild(nextBtn);
        }
    } else if (paginationButtons) {
        paginationButtons.innerHTML = '';
    }
}

function goToPagePasados(page) {
    currentPagePasados = page;
    updateDisplayPasados();
}

function changeRecordsPerPagePasados() {
    const select = document.getElementById('recordsPerPagePasados');
    recordsPerPagePasados = select.value === 'all' ? 'all' : parseInt(select.value);
    currentPagePasados = 1;
    updateDisplayPasados();
}

// Inicializar paginación al cargar la página
window.addEventListener('DOMContentLoaded', function() {
    updateDisplayProximos();
    updateDisplayPasados();
});
</script>
