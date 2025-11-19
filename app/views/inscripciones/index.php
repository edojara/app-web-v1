<?php
/**
 * Función para abreviar Universidad a Univ en visualización
 */
function abreviarInstitucion($nombre) {
    if (!$nombre) return $nombre;
    return str_replace('Universidad', 'Univ', $nombre);
}
?>
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
            <a href="?url=eventos" class="btn">← Volver a Eventos</a>
        </div>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="color: #666; margin-bottom: 10px;">📝 Inscripciones</h3>
            <h1 style="margin-bottom: 10px;"><?php echo htmlspecialchars($evento['nombre']); ?></h1>
            <p style="color: #666; margin-top: 5px;">
                📅 <?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?> - 
                <?php echo date('d/m/Y', strtotime($evento['fecha_termino'])); ?> | 
                📍 <?php echo htmlspecialchars($evento['lugar']); ?>
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: center; margin-bottom: 20px;">
            <button onclick="openInscribirModal()" class="btn btn-primary">
                ➕ Inscribir Participantes
            </button>
            <button onclick="openInscribirCSVModal()" class="btn" style="background: #27ae60; color: white;">
                📄 Inscripción Masiva (CSV)
            </button>
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

        <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196f3;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 24px;">👥</span>
                <div>
                    <strong style="font-size: 18px; color: #1976d2;">Total Inscritos:</strong> 
                    <span style="font-size: 24px; font-weight: bold; color: #1976d2;"><?php echo $totalInscritos; ?></span>
                </div>
            </div>
        </div>

        <!-- Tabla de inscritos -->
        <div style="margin-top: 20px;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Participantes Inscritos</h2>
            
            <?php if (!empty($inscripciones)): ?>
                <div style="display: grid; grid-template-columns: 50px 2.5fr 1.2fr 2fr 2fr 1.5fr 1.5fr 100px; gap: 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">#</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Nombre Completo</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">RUT</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Institución</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Email</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Teléfono</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Fecha Inscripción</div>
                    <div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; text-align: center;">Acciones</div>
                    
                    <?php foreach ($inscripciones as $index => $inscripcion): 
                        $bgColor = $index % 2 == 0 ? '#f8f9fa' : 'white';
                    ?>
                        <!-- Fila <?php echo $index + 1; ?> -->
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
                            <strong><?php echo $index + 1; ?></strong>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
                            <a href="javascript:void(0)" 
                               onclick="verDetalleParticipante(<?php echo $inscripcion['participante_id']; ?>)"
                               style="color: #1976d2; text-decoration: none; font-weight: bold; cursor: pointer;"
                               onmouseover="this.style.textDecoration='underline'"
                               onmouseout="this.style.textDecoration='none'"
                               title="Ver detalles del participante">
                                <?php echo htmlspecialchars($inscripcion['nombre_completo']); ?>
                            </a>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
                            <?php echo htmlspecialchars($inscripcion['rut']); ?>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
                            <span style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                                <?php echo htmlspecialchars(abreviarInstitucion($inscripcion['institucion_nombre'] ?? 'Sin institución')); ?>
                            </span>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center; font-size: 13px;">
                            📧 <?php echo htmlspecialchars($inscripcion['email']); ?>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center; font-size: 13px;">
                            📞 <?php echo htmlspecialchars($inscripcion['telefono']); ?>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center; font-size: 13px;">
                            🗓️ <?php echo date('d/m/Y', strtotime($inscripcion['fecha_inscripcion'])); ?>
                        </div>
                        <div style="background: <?php echo $bgColor; ?>; padding: 12px; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: center;">
                            <button onclick="confirmarEliminar(<?php echo $inscripcion['id']; ?>, '<?php echo htmlspecialchars($inscripcion['nombre_completo'], ENT_QUOTES); ?>')" 
                                    style="background: transparent; color: #f44336; border: none; padding: 8px 12px; cursor: pointer; font-size: 20px; transition: color 0.3s;"
                                    onmouseover="this.style.color='#d32f2f'" 
                                    onmouseout="this.style.color='#f44336'"
                                    title="Eliminar inscripción">
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
                                            Institución: <?php echo htmlspecialchars(abreviarInstitucion($participante['institucion_nombre'] ?? 'N/A')); ?>
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
        closeInscribirCSVModal();
    }
});

// Funciones para modal CSV
function openInscribirCSVModal() {
    document.getElementById('inscribirCSVModal').style.display = 'block';
}

function closeInscribirCSVModal() {
    document.getElementById('inscribirCSVModal').style.display = 'none';
    document.getElementById('csvFile').value = '';
    document.getElementById('csvPreview').innerHTML = '';
}

function previewCSV() {
    const fileInput = document.getElementById('csvFile');
    const file = fileInput.files[0];
    const preview = document.getElementById('csvPreview');
    
    if (!file) {
        preview.innerHTML = '<p style="color: #999;">No hay archivo seleccionado</p>';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const text = e.target.result;
        const lines = text.split('\n').filter(line => line.trim());
        
        let html = '<div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 10px;">';
        html += '<strong>Vista previa del archivo (' + lines.length + ' líneas):</strong><br><br>';
        html += '<div style="max-height: 200px; overflow-y: auto; background: white; padding: 10px; border-radius: 4px;">';
        
        lines.slice(0, 20).forEach((line, index) => {
            const rut = line.trim();
            if (rut) {
                html += '<div style="padding: 4px; border-bottom: 1px solid #eee;">';
                html += (index + 1) + '. ' + rut;
                html += '</div>';
            }
        });
        
        if (lines.length > 20) {
            html += '<div style="padding: 8px; color: #666; font-style: italic;">... y ' + (lines.length - 20) + ' más</div>';
        }
        
        html += '</div></div>';
        preview.innerHTML = html;
    };
    
    reader.readAsText(file);
}

// Cerrar modal CSV al hacer clic fuera
window.addEventListener('click', function(event) {
    const csvModal = document.getElementById('inscribirCSVModal');
    if (event.target == csvModal) {
        closeInscribirCSVModal();
    }
});
</script>

<!-- Modal para inscripción masiva CSV -->
<div id="inscribirCSVModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2>📄 Inscripción Masiva desde CSV</h2>
            <span class="close" onclick="closeInscribirCSVModal()">&times;</span>
        </div>
        <form method="POST" action="?url=inscripciones/importCSV" enctype="multipart/form-data">
            <input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
            
            <div class="form-group">
                <label>Archivo CSV con RUTs:</label>
                <input type="file" 
                       id="csvFile" 
                       name="csv_file" 
                       accept=".csv,.txt" 
                       required
                       onchange="previewCSV()"
                       class="form-control">
                <small style="color: #666; display: block; margin-top: 5px;">
                    📝 El archivo debe contener un RUT por línea (ej: 12.345.678-9)
                </small>
            </div>
            
            <div id="csvPreview"></div>
            
            <div class="form-group">
                <label>Observaciones (opcional):</label>
                <textarea name="observaciones" 
                          class="form-control" 
                          rows="2" 
                          placeholder="Observaciones generales para todas las inscripciones..."></textarea>
            </div>
            
            <div style="background: #e3f2fd; padding: 12px; border-radius: 4px; margin: 15px 0;">
                <strong style="color: #1976d2;">ℹ️ Formato del archivo CSV:</strong>
                <ul style="margin: 8px 0 0 20px; color: #666;">
                    <li>Un RUT por línea</li>
                    <li>Formato: 12.345.678-9 o 12345678-9</li>
                    <li>Se ignorarán líneas vacías</li>
                    <li>Solo se inscribirán participantes que existan en el sistema</li>
                </ul>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">📤 Importar e Inscribir</button>
                <button type="button" onclick="closeInscribirCSVModal()" class="btn">Cancelar</button>
            </div>
        </form>
    </div>
</div>

