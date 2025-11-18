<div class="container">
    <div class="page-header">
        <h1>🏛️ <?php echo htmlspecialchars($institucion['nombre']); ?></h1>
        <div class="page-actions">
            <a href="<?php echo APP_URL; ?>/?url=instituciones" class="btn btn-secondary">← Volver</a>
            <a href="<?php echo APP_URL; ?>/?url=instituciones/edit&id=<?php echo $institucion['id']; ?>" class="btn btn-primary">✏️ Editar</a>
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

    <!-- Información de la Institución -->
    <div class="card">
        <h2>📋 Información General</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre:</strong>
                <span><?php echo htmlspecialchars($institucion['nombre']); ?></span>
            </div>
            <div class="info-item">
                <strong>Dirección:</strong>
                <span><?php echo htmlspecialchars($institucion['direccion']); ?></span>
            </div>
            <div class="info-item">
                <strong>Ciudad:</strong>
                <span><?php echo htmlspecialchars($institucion['ciudad']); ?></span>
            </div>
            <div class="info-item">
                <strong>Estado:</strong>
                <?php if ($institucion['estado'] === 'activa'): ?>
                    <span class="badge badge-success">✓ Activa</span>
                <?php else: ?>
                    <span class="badge badge-warning">✗ Inactiva</span>
                <?php endif; ?>
            </div>
            <div class="info-item">
                <strong>Fecha de Creación:</strong>
                <span><?php echo date('d/m/Y H:i', strtotime($institucion['created_at'])); ?></span>
            </div>
            <div class="info-item">
                <strong>Última Actualización:</strong>
                <span><?php echo date('d/m/Y H:i', strtotime($institucion['updated_at'])); ?></span>
            </div>
        </div>
    </div>

    <!-- Contactos -->
    <div class="card">
        <div class="section-header">
            <h2>👥 Personas de Contacto</h2>
            <button onclick="toggleContactForm()" class="btn btn-success" id="btnShowForm">➕ Agregar Contacto</button>
        </div>

        <!-- Formulario para agregar contacto -->
        <div id="contactForm" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background-color: #f8f9fa; border-radius: 4px;">
            <h3 style="margin-top: 0;">Nuevo Contacto</h3>
            <form method="POST" action="<?php echo APP_URL; ?>/?url=instituciones/addContacto&institucion_id=<?php echo $institucion['id']; ?>">
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="nombre_completo" class="required">Nombre Completo</label>
                        <input type="text" 
                               id="nombre_completo" 
                               name="nombre_completo" 
                               class="form-control" 
                               required
                               maxlength="255"
                               placeholder="Ej: Juan Pérez García">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="ocupacion" class="required">Ocupación</label>
                        <input type="text" 
                               id="ocupacion" 
                               name="ocupacion" 
                               class="form-control" 
                               required
                               maxlength="100"
                               placeholder="Ej: Director">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="telefono">Teléfono</label>
                        <input type="text" 
                               id="telefono" 
                               name="telefono" 
                               class="form-control" 
                               maxlength="20"
                               placeholder="Ej: +56 9 1234 5678">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="email">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               maxlength="255"
                               placeholder="Ej: contacto@institucion.cl">
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 1rem; border-top: none; padding-top: 0;">
                    <button type="button" onclick="toggleContactForm()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-success">💾 Guardar Contacto</button>
                </div>
            </form>
        </div>

        <!-- Lista de contactos -->
        <?php if (empty($contactos)): ?>
            <p style="text-align: center; color: #666; padding: 2rem;">
                No hay contactos registrados para esta institución.
            </p>
        <?php else: ?>
            <table class="table" style="margin-top: 1.5rem;">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Ocupación</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $contacto): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($contacto['nombre_completo']); ?></strong></td>
                            <td><?php echo htmlspecialchars($contacto['ocupacion']); ?></td>
                            <td><?php echo $contacto['telefono'] ? htmlspecialchars($contacto['telefono']) : '<em style="color: #999;">No especificado</em>'; ?></td>
                            <td><?php echo $contacto['email'] ? htmlspecialchars($contacto['email']) : '<em style="color: #999;">No especificado</em>'; ?></td>
                            <td class="text-center">
                                <a href="<?php echo APP_URL; ?>/?url=instituciones/deleteContacto&id=<?php echo $contacto['id']; ?>&institucion_id=<?php echo $institucion['id']; ?>" 
                                   class="btn-action btn-delete" 
                                   title="Eliminar contacto"
                                   onclick="return confirm('¿Estás seguro de eliminar este contacto?');">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Participantes -->
    <div class="card">
        <div class="section-header">
            <h2>👨‍🎓 Participantes</h2>
            <button onclick="toggleParticipanteForm()" class="btn btn-success" id="btnShowParticipanteForm">➕ Agregar Participante</button>
        </div>

        <!-- Campo de búsqueda para participantes -->
        <?php if (!empty($participantes)): ?>
            <div style="margin-top: 1rem; margin-bottom: 1rem;">
                <input type="text" 
                       id="searchParticipantes" 
                       placeholder="🔍 Buscar participante por nombre, RUT o teléfono..." 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
            </div>
        <?php endif; ?>

        <!-- Formulario para agregar participante -->
        <div id="participanteForm" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background-color: #f8f9fa; border-radius: 4px;">
            <h3 style="margin-top: 0;">Nuevo Participante</h3>
            <form method="POST" action="<?php echo APP_URL; ?>/?url=instituciones/addParticipante">
                <input type="hidden" name="institucion_id" value="<?php echo $institucion['id']; ?>">
                
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="nombre_completo_part" class="required">Nombre Completo</label>
                        <input type="text" 
                               id="nombre_completo_part" 
                               name="nombre_completo" 
                               class="form-control" 
                               required
                               maxlength="255"
                               placeholder="Ej: Juan Pérez García">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="rut" class="required">RUT</label>
                        <input type="text" 
                               id="rut" 
                               name="rut" 
                               class="form-control" 
                               required
                               maxlength="12"
                               placeholder="Ej: 12.345.678-9"
                               pattern="[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]{1}"
                               title="Formato: 12.345.678-9">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="telefono_part">Teléfono</label>
                        <input type="text" 
                               id="telefono_part" 
                               name="telefono" 
                               class="form-control" 
                               maxlength="20"
                               placeholder="Ej: +56 9 1234 5678">
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 1rem; border-top: none; padding-top: 0;">
                    <button type="button" onclick="toggleParticipanteForm()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-success">💾 Guardar Participante</button>
                </div>
            </form>
        </div>

        <!-- Lista de participantes -->
        <?php if (empty($participantes)): ?>
            <p style="text-align: center; color: #666; padding: 2rem;">
                No hay participantes registrados para esta institución.
            </p>
        <?php else: ?>
            <table class="table" style="margin-top: 1.5rem;">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>RUT</th>
                        <th>Teléfono</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $participante): ?>
                        <tr class="participante-row" 
                            data-nombre="<?= htmlspecialchars(strtolower($participante['nombre_completo'])) ?>"
                            data-rut="<?= htmlspecialchars(strtolower($participante['rut'])) ?>"
                            data-telefono="<?= htmlspecialchars(strtolower($participante['telefono'] ?? '')) ?>">
                            <td><strong><?php echo htmlspecialchars($participante['nombre_completo']); ?></strong></td>
                            <td><?php echo htmlspecialchars($participante['rut']); ?></td>
                            <td><?php echo $participante['telefono'] ? htmlspecialchars($participante['telefono']) : '<em style="color: #999;">No especificado</em>'; ?></td>
                            <td class="text-center">
                                <a href="<?php echo APP_URL; ?>/?url=instituciones/deleteParticipante&id=<?php echo $participante['id']; ?>&institucion_id=<?php echo $institucion['id']; ?>" 
                                   class="btn-action btn-delete" 
                                   title="Eliminar participante"
                                   onclick="return confirm('¿Estás seguro de eliminar este participante?');">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item strong {
    color: #666;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    color: #333;
    font-size: 1rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.section-header h2 {
    margin: 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background-color: var(--primary-color);
    color: white;
}

.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
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
    transition: all 0.2s ease;
    font-size: 1rem;
}

.btn-delete {
    background-color: #ffebee;
}

.btn-delete:hover {
    background-color: #d32f2f;
    transform: scale(1.1);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-group label.required::after {
    content: " *";
    color: var(--danger-color);
}

.form-control {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.form-row {
    display: flex;
    gap: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e0e0e0;
}

.btn {
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s ease;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: #1976d2;
}

.btn-success {
    background-color: var(--success-color, #27ae60);
    color: white;
}

.btn-success:hover {
    background-color: #229954;
}
</style>

<script>
function toggleContactForm() {
    const form = document.getElementById('contactForm');
    const btn = document.getElementById('btnShowForm');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        btn.textContent = '✖ Cancelar';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
    } else {
        form.style.display = 'none';
        btn.textContent = '➕ Agregar Contacto';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-success');
        // Limpiar formulario
        form.querySelector('form').reset();
    }
}

function toggleParticipanteForm() {
    const form = document.getElementById('participanteForm');
    const btn = document.getElementById('btnShowParticipanteForm');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        btn.textContent = '✖ Cancelar';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
    } else {
        form.style.display = 'none';
        btn.textContent = '➕ Agregar Participante';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-success');
        // Limpiar formulario
        form.querySelector('form').reset();
    }
}

function filterParticipantes() {
    const searchInput = document.getElementById('searchParticipantes');
    if (!searchInput) return;
    
    const searchValue = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.participante-row');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const nombre = (row.getAttribute('data-nombre') || '').toLowerCase();
        const rut = (row.getAttribute('data-rut') || '').toLowerCase();
        const telefono = (row.getAttribute('data-telefono') || '').toLowerCase();
        
        const matches = nombre.includes(searchValue) || 
                       rut.includes(searchValue) || 
                       telefono.includes(searchValue);
        
        if (matches) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

// Inicializar event listeners al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchParticipantes');
    if (searchInput) {
        // Filtrado en tiempo real
        searchInput.addEventListener('input', function() {
            filterParticipantes();
        });
        
        // Limpiar filtro con Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                filterParticipantes();
                searchInput.blur();
            }
        });
    }
});
</script>
