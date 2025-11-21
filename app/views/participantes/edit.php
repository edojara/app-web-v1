<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Participante</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/?url=participantes/update" id="participanteForm">
                        <input type="hidden" name="id" value="<?= $participante['id'] ?>">

                        <div class="form-group">
                            <label for="institucion_id">Institución <span class="text-danger">*</span></label>
                            <select class="form-control" id="institucion_id" name="institucion_id" required>
                                <option value="">Seleccione una institución</option>
                                <?php foreach ($instituciones as $institucion): ?>
                                    <?php if ($institucion['estado'] === 'activa' || $institucion['id'] == $participante['institucion_id']): ?>
                                        <option value="<?= $institucion['id'] ?>" 
                                                <?= ($participante['institucion_id'] == $institucion['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($institucion['nombre']) ?>
                                            <?= $institucion['estado'] !== 'activa' ? ' (Inactiva)' : '' ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Seleccione la institución a la que pertenece</small>
                        </div>

                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre_completo" 
                                   name="nombre_completo" 
                                   value="<?= htmlspecialchars($participante['nombre_completo']) ?>"
                                   required
                                   maxlength="255">
                            <small class="form-text text-muted">Ingrese el nombre completo del participante</small>
                        </div>

                        <div class="form-group">
                            <label for="rut">RUT <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="rut" 
                                   name="rut" 
                                   value="<?= htmlspecialchars($participante['rut']) ?>"
                                   required
                                   pattern="[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]{1}"
                                   title="Formato: 12.345.678-9"
                                   placeholder="12.345.678-9"
                                   maxlength="12">
                            <small class="form-text text-muted">Formato: XX.XXX.XXX-X (ej: 12.345.678-9)</small>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="<?= htmlspecialchars($participante['telefono'] ?? '') ?>"
                                   maxlength="20"
                                   placeholder="+56 9 1234 5678">
                            <small class="form-text text-muted">Número de contacto del participante</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($participante['email'] ?? '') ?>"
                                   maxlength="255"
                                   placeholder="correo@ejemplo.cl">
                            <small class="form-text text-muted">Correo electrónico del participante</small>
                        </div>

                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="cargo" 
                                   name="cargo" 
                                   value="<?= htmlspecialchars($participante['cargo'] ?? '') ?>"
                                   maxlength="100"
                                   placeholder="Ej: Profesor, Director, Coordinador">
                            <small class="form-text text-muted">Cargo o función del participante</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                💾 Guardar Cambios
                            </button>
                            <a href="/?url=participantes/view&id=<?= $participante['id'] ?>" class="btn btn-secondary">
                                ❌ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Formatear RUT automáticamente
document.getElementById('rut').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\dkK]/g, '');
    
    if (value.length > 1) {
        let rut = value.slice(0, -1);
        let dv = value.slice(-1);
        
        rut = rut.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        
        e.target.value = rut + '-' + dv;
    }
});

// Validar formulario
document.getElementById('participanteForm').addEventListener('submit', function(e) {
    const rut = document.getElementById('rut').value;
    const nombre = document.getElementById('nombre_completo').value.trim();
    const institucion = document.getElementById('institucion_id').value;
    
    if (!nombre || !rut || !institucion) {
        e.preventDefault();
        alert('Por favor, complete todos los campos obligatorios');
        return false;
    }
});
</script>
