<div class="container">
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
        <div class="card">
            <p style="text-align: center; color: #666; padding: 2rem;">
                No hay instituciones registradas. 
                <a href="<?php echo APP_URL; ?>/?url=instituciones/create">Crear la primera institución</a>
            </p>
        </div>
    <?php else: ?>
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instituciones as $inst): ?>
                        <tr ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=instituciones/view&id=<?php echo $inst['id']; ?>'" style="cursor: pointer;" title="Doble click para ver detalles">
                            <td><?php echo $inst['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($inst['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($inst['ciudad']); ?></td>
                            <td><?php echo htmlspecialchars($inst['direccion']); ?></td>
                            <td>
                                <?php if ($inst['estado'] === 'activa'): ?>
                                    <span class="badge badge-success">✓ Activa</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">✗ Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <a href="<?php echo APP_URL; ?>/?url=instituciones/view&id=<?php echo $inst['id']; ?>" 
                                       class="btn-action btn-view" 
                                       title="Ver detalles">
                                        👁️
                                    </a>
                                    <a href="<?php echo APP_URL; ?>/?url=instituciones/edit&id=<?php echo $inst['id']; ?>" 
                                       class="btn-action btn-edit" 
                                       title="Editar">
                                        ✏️
                                    </a>
                                    <a href="<?php echo APP_URL; ?>/?url=instituciones/delete&id=<?php echo $inst['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta institución? Se eliminarán también todos sus contactos.');">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
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

.btn-view {
    background-color: #e3f2fd;
}

.btn-view:hover {
    background-color: #2196f3;
    transform: scale(1.1);
}

.btn-edit {
    background-color: #e3f2fd;
}

.btn-edit:hover {
    background-color: #1976d2;
    transform: scale(1.1);
}

.btn-delete {
    background-color: #ffebee;
}

.btn-delete:hover {
    background-color: #d32f2f;
    transform: scale(1.1);
}
</style>
