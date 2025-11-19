<div class="container-fluid" style="max-width: 98%; padding: 0 1rem;">
    <div class="content-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; margin-top: 1.5rem;">
        <input 
            type="text" 
            id="searchInput" 
            class="form-control" 
            placeholder="Buscar por nombre, lugar..." 
            style="flex: 1; max-width: 50%;">
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
                    <h2 style="margin-bottom: 1.5rem; color: #2196f3;">🔔 EVENTOS PRÓXIMOS</h2>
                    <div class="table-responsive" style="margin-bottom: 3rem;">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre</th>
                                    <th style="width: 180px;">Fecha Inicio</th>
                                    <th style="width: 180px;">Fecha Término</th>
                                    <th>Lugar</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $numero = 1; ?>
                                <?php foreach ($eventosProximos as $evento): ?>
                                    <tr ondblclick="window.location.href='/?url=eventos/view&id=<?= $evento['id'] ?>'" style="cursor: pointer;">
                                        <td><?= $numero++ ?></td>
                                        <td><strong><?= htmlspecialchars($evento['nombre']) ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($evento['fecha_termino'])) ?></td>
                                        <td><?= htmlspecialchars($evento['lugar']) ?></td>
                                        <td onclick="event.stopPropagation();" ondblclick="event.stopPropagation();">
                                            <button onclick="event.stopPropagation(); editEvento(<?= $evento['id'] ?>, '<?= htmlspecialchars($evento['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($evento['descripcion'] ?? '', ENT_QUOTES) ?>', '<?= $evento['fecha_inicio'] ?>', '<?= $evento['fecha_termino'] ?>', '<?= htmlspecialchars($evento['lugar'], ENT_QUOTES) ?>')" 
                                               class="btn btn-sm btn-primary" 
                                               title="Editar">✏️</button>
                                            <a href="/?url=eventos/delete&id=<?= $evento['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="event.stopPropagation(); return confirm('¿Está seguro de eliminar este evento?')"
                                               title="Eliminar">🗑️</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Eventos Pasados -->
                <?php if (!empty($eventosPasados)): ?>
                    <h2 style="margin-bottom: 1.5rem; color: #757575;">📋 EVENTOS REALIZADOS</h2>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre</th>
                                    <th style="width: 180px;">Fecha Inicio</th>
                                    <th style="width: 180px;">Fecha Término</th>
                                    <th>Lugar</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $numero = 1; ?>
                                <?php foreach ($eventosPasados as $evento): ?>
                                    <tr ondblclick="window.location.href='/?url=eventos/view&id=<?= $evento['id'] ?>'" style="cursor: pointer; opacity: 0.7;">
                                        <td><?= $numero++ ?></td>
                                        <td><strong><?= htmlspecialchars($evento['nombre']) ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($evento['fecha_termino'])) ?></td>
                                        <td><?= htmlspecialchars($evento['lugar']) ?></td>
                                        <td onclick="event.stopPropagation();" ondblclick="event.stopPropagation();">
                                            <button onclick="event.stopPropagation(); editEvento(<?= $evento['id'] ?>, '<?= htmlspecialchars($evento['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($evento['descripcion'] ?? '', ENT_QUOTES) ?>', '<?= $evento['fecha_inicio'] ?>', '<?= $evento['fecha_termino'] ?>', '<?= htmlspecialchars($evento['lugar'], ENT_QUOTES) ?>')" 
                                               class="btn btn-sm btn-primary" 
                                               title="Editar">✏️</button>
                                            <a href="/?url=eventos/delete&id=<?= $evento['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="event.stopPropagation(); return confirm('¿Está seguro de eliminar este evento?')"
                                               title="Eliminar">🗑️</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                            Fecha y Hora de Inicio <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="datetime-local" id="create_fecha_inicio" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="create_fecha_termino" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha y Hora de Término <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="datetime-local" id="create_fecha_termino" name="fecha_termino" class="form-control" required>
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
                            Fecha y Hora de Inicio <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="datetime-local" id="edit_fecha_inicio" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_fecha_termino" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Fecha y Hora de Término <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="datetime-local" id="edit_fecha_termino" name="fecha_termino" class="form-control" required>
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

// Filtrado en tiempo real
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Limpiar con Escape
document.getElementById('searchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        this.value = '';
        document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
        this.blur();
    }
});
</script>
