<div class="container">
    <div class="page-header">
        <h1>✏️ Editar Institución</h1>
    </div>

    <div class="card">
        <form method="POST" action="<?php echo APP_URL; ?>/?url=instituciones/edit&id=<?php echo $institucion['id']; ?>">
            <div class="form-group">
                <label for="nombre" class="required">Nombre de la Institución</label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       class="form-control" 
                       required
                       maxlength="255"
                       value="<?php echo htmlspecialchars($institucion['nombre']); ?>"
                       placeholder="Ej: Universidad Nacional">
            </div>

            <div class="form-group">
                <label for="direccion" class="required">Dirección</label>
                <input type="text" 
                       id="direccion" 
                       name="direccion" 
                       class="form-control" 
                       required
                       maxlength="500"
                       value="<?php echo htmlspecialchars($institucion['direccion']); ?>"
                       placeholder="Ej: Av. Libertador 1234">
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label for="ciudad" class="required">Ciudad</label>
                    <input type="text" 
                           id="ciudad" 
                           name="ciudad" 
                           class="form-control" 
                           required
                           maxlength="100"
                           value="<?php echo htmlspecialchars($institucion['ciudad']); ?>"
                           placeholder="Ej: Santiago">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="estado" class="required">Estado</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="activa" <?php echo $institucion['estado'] === 'activa' ? 'selected' : ''; ?>>
                            ✓ Activa
                        </option>
                        <option value="inactiva" <?php echo $institucion['estado'] === 'inactiva' ? 'selected' : ''; ?>>
                            ✗ Inactiva
                        </option>
                    </select>
                </div>
            </div>

            <div class="info-box">
                <p><strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($institucion['created_at'])); ?></p>
                <p><strong>Última modificación:</strong> <?php echo date('d/m/Y H:i', strtotime($institucion['updated_at'])); ?></p>
            </div>

            <div class="form-actions">
                <a href="<?php echo APP_URL; ?>/?url=instituciones/view&id=<?php echo $institucion['id']; ?>" class="btn btn-secondary">
                    ← Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 1.5rem;
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

.info-box {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #6c757d;
}

.info-box p {
    margin: 0.25rem 0;
    color: #666;
    font-size: 0.875rem;
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

.btn-success {
    background-color: var(--success-color, #27ae60);
    color: white;
}

.btn-success:hover {
    background-color: #229954;
}
</style>
