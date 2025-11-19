<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1>📝 Inscripciones - <?php echo htmlspecialchars($evento['nombre']); ?></h1>
                <p style="color: #666; margin-top: 5px;">
                    📅 <?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?> - 
                    <?php echo date('d/m/Y', strtotime($evento['fecha_termino'])); ?> | 
                    📍 <?php echo htmlspecialchars($evento['lugar']); ?>
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button onclick="openInscribirModal()" class="btn btn-primary">
                    ➕ Inscribir Participantes
                </button>
                <a href="?url=eventos" class="btn">← Volver a Eventos</a>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid #ffc107; color: #856404;">
                <?php 
                echo $_SESSION['warning']; 
                unset($_SESSION['warning']);
                ?>
            </div>
        <?php endif; ?>

        <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>👥 Total Inscritos:</strong> <?php echo $totalInscritos; ?>
        </div>

        <!-- Tabla de inscritos -->
        <div style="margin-top: 20px;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Participantes Inscritos</h2>
            
            <?php if (!empty($inscripciones)): ?>
                <div class="grid-table" style="grid-template-columns: 50px 2fr 1.5fr 1.5fr 1.5fr 1.5fr 1.2fr 120px;">
                    <div class="grid-header">#</div>
                    <div class="grid-header">Nombre Completo</div>
                    <div class="grid-header">RUT</div>
                    <div class="grid-header">Institución</div>
                    <div class="grid-header">Email</div>
                    <div class="grid-header">Teléfono</div>
                    <div class="grid-header">Fecha Inscripción</div>
                    <div class="grid-header">Acciones</div>
                    
                    <?php foreach ($inscripciones as $index => $inscripcion): ?>
                        <div class="grid-cell"><?php echo $index + 1; ?></div>
                        <div class="grid-cell"><?php echo htmlspecialchars($inscripcion['nombre_completo']); ?></div>
                        <div class="grid-cell"><?php echo htmlspecialchars($inscripcion['rut']); ?></div>
                        <div class="grid-cell"><?php echo htmlspecialchars($inscripcion['institucion_nombre'] ?? 'N/A'); ?></div>
                        <div class="grid-cell"><?php echo htmlspecialchars($inscripcion['email']); ?></div>
                        <div class="grid-cell"><?php echo htmlspecialchars($inscripcion['telefono']); ?></div>
                        <div class="grid-cell"><?php echo date('d/m/Y H:i', strtotime($inscripcion['fecha_inscripcion'])); ?></div>
                        <div class="grid-cell">
                            <button onclick="confirmarEliminar(<?php echo $inscripcion['id']; ?>, '<?php echo htmlspecialchars($inscripcion['nombre_completo']); ?>')" 
                                    class="btn-icon" title="Eliminar inscripción">
                                🗑️
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 40px;">
                    No hay participantes inscritos en este evento
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para inscribir participantes -->
<div id="inscribirModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2>➕ Inscribir Participantes</h2>
            <span class="close" onclick="closeInscribirModal()">&times;</span>
        </div>
        <form method="POST" action="?url=inscripciones/store" onsubmit="return validateInscripcion()">
            <input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
            
            <div class="form-group">
                <label>Buscar Participante:</label>
                <input type="text" id="searchParticipante" onkeyup="filterParticipantes()" 
                       placeholder="Buscar por nombre, RUT o institución..." class="form-control">
            </div>
            
            <div class="form-group">
                <label>Seleccionar Participantes: <span style="color: red;">*</span></label>
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px;">
                    <?php if (!empty($participantesDisponibles)): ?>
                        <div style="margin-bottom: 10px;">
                            <label style="font-weight: normal; cursor: pointer;">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"> 
                                Seleccionar todos
                            </label>
                        </div>
                        <?php foreach ($participantesDisponibles as $participante): ?>
                            <div class="participante-item" style="padding: 8px; margin-bottom: 5px; background: #f5f5f5; border-radius: 4px;"
                                 data-nombre="<?php echo strtolower($participante['nombre_completo']); ?>"
                                 data-rut="<?php echo strtolower($participante['rut']); ?>"
                                 data-institucion="<?php echo strtolower($participante['institucion_nombre'] ?? ''); ?>">
                                <label style="display: flex; align-items: center; cursor: pointer; font-weight: normal;">
                                    <input type="checkbox" name="participante_ids[]" value="<?php echo $participante['id']; ?>" class="participante-checkbox" style="margin-right: 10px;">
                                    <div style="flex: 1;">
                                        <strong><?php echo htmlspecialchars($participante['nombre_completo']); ?></strong><br>
                                        <small style="color: #666;">
                                            RUT: <?php echo htmlspecialchars($participante['rut']); ?> | 
                                            Institución: <?php echo htmlspecialchars($participante['institucion_nombre'] ?? 'N/A'); ?>
                                        </small>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #999; padding: 20px;">
                            Todos los participantes ya están inscritos en este evento
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Observaciones:</label>
                <textarea name="observaciones" class="form-control" rows="3" 
                          placeholder="Observaciones adicionales (opcional)"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn" onclick="closeInscribirModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Inscribir Seleccionados</button>
            </div>
        </form>
    </div>
</div>

<script>
function openInscribirModal() {
    document.getElementById('inscribirModal').style.display = 'block';
}

function closeInscribirModal() {
    document.getElementById('inscribirModal').style.display = 'none';
    document.getElementById('searchParticipante').value = '';
    filterParticipantes();
}

function validateInscripcion() {
    const checkboxes = document.querySelectorAll('input[name="participante_ids[]"]:checked');
    if (checkboxes.length === 0) {
        alert('Debe seleccionar al menos un participante');
        return false;
    }
    return true;
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.participante-checkbox');
    const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
        return cb.closest('.participante-item').style.display !== 'none';
    });
    
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function filterParticipantes() {
    const searchText = document.getElementById('searchParticipante').value.toLowerCase();
    const items = document.querySelectorAll('.participante-item');
    
    items.forEach(item => {
        const nombre = item.dataset.nombre;
        const rut = item.dataset.rut;
        const institucion = item.dataset.institucion;
        
        if (nombre.includes(searchText) || rut.includes(searchText) || institucion.includes(searchText)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Desmarcar "seleccionar todos" si hay filtro activo
    if (searchText) {
        document.getElementById('selectAll').checked = false;
    }
}

function confirmarEliminar(id, nombre) {
    if (confirm(`¿Está seguro de eliminar la inscripción de ${nombre}?`)) {
        window.location.href = `?url=inscripciones/delete&id=${id}&evento_id=<?php echo $evento_id; ?>`;
    }
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('inscribirModal');
    if (event.target == modal) {
        closeInscribirModal();
    }
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeInscribirModal();
    }
});
</script>
