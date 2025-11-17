<div class="container">
    <div class="page-header">
        <h1>📋 Historial de Auditoría</h1>
        <p class="subtitle">Registro completo de cambios realizados en el sistema</p>
    </div>

    <!-- Estadísticas -->
    <?php if (!empty($estadisticas)): ?>
    <div class="stats-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="text-align: center;">
            <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;"><?php echo number_format($estadisticas['total_logs']); ?></h3>
            <p style="color: #666; font-size: 0.9rem;">Total de Registros</p>
        </div>
        <div class="card" style="text-align: center;">
            <h3 style="color: var(--success-color); margin-bottom: 0.5rem;"><?php echo number_format($estadisticas['usuarios_activos']); ?></h3>
            <p style="color: #666; font-size: 0.9rem;">Usuarios Activos</p>
        </div>
        <div class="card" style="text-align: center;">
            <h3 style="color: var(--warning-color); margin-bottom: 0.5rem;"><?php echo number_format($estadisticas['dias_activos']); ?></h3>
            <p style="color: #666; font-size: 0.9rem;">Días con Actividad</p>
        </div>
        <div class="card" style="text-align: center;">
            <h3 style="color: var(--primary-color); margin-bottom: 0.5rem; font-size: 1rem;">
                <?php echo $estadisticas['ultima_actividad'] ? date('d/m/Y H:i', strtotime($estadisticas['ultima_actividad'])) : 'N/A'; ?>
            </h3>
            <p style="color: #666; font-size: 0.9rem;">Última Actividad</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="card" style="margin-bottom: 2rem;">
        <form method="GET" action="<?php echo APP_URL; ?>/?url=audit" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="usuario_id">Usuario que realizó la acción</label>
                <select name="usuario_id" id="usuario_id">
                    <option value="">Todos</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['id']; ?>" <?php echo (isset($_GET['usuario_id']) && $_GET['usuario_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($usuario['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="accion">Tipo de Acción</label>
                <select name="accion" id="accion">
                    <option value="">Todas</option>
                    <?php foreach ($acciones as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($_GET['accion']) && $_GET['accion'] == $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="fecha_desde">Fecha Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="fecha_hasta">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>">
            </div>

            <div style="display: flex; gap: 0.5rem; align-items: end;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">🔍 Filtrar</button>
                <a href="<?php echo APP_URL; ?>/?url=audit" class="btn btn-secondary">🔄 Limpiar</a>
            </div>
        </form>
    </div>

    <!-- Tabla de Logs -->
    <div class="card">
        <?php if (empty($logs)): ?>
            <p style="text-align: center; color: #666; padding: 2rem;">No hay registros de auditoría para mostrar</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Fecha/Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Usuario Afectado</th>
                            <th>Cambios</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo $log['id']; ?></td>
                                <td>
                                    <small><?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($log['usuario_nombre'] ?? 'Sistema'); ?></strong>
                                    <br>
                                    <small style="color: #666;"><?php echo htmlspecialchars($log['usuario_email'] ?? ''); ?></small>
                                </td>
                                <td>
                                    <?php 
                                        $accionLabel = $acciones[$log['accion']] ?? $log['accion'];
                                        $badgeClass = 'badge-secondary';
                                        if (strpos($log['accion'], 'crear') !== false) $badgeClass = 'badge-success';
                                        if (strpos($log['accion'], 'eliminar') !== false) $badgeClass = 'badge-danger';
                                        if (strpos($log['accion'], 'editar') !== false) $badgeClass = 'badge-primary';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $accionLabel; ?></span>
                                </td>
                                <td>
                                    <?php if ($log['usuario_afectado_nombre']): ?>
                                        <strong><?php echo htmlspecialchars($log['usuario_afectado_nombre']); ?></strong>
                                        <br>
                                        <small style="color: #666;"><?php echo htmlspecialchars($log['usuario_afectado_email']); ?></small>
                                    <?php else: ?>
                                        <em style="color: #999;">N/A</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($log['cambios']): ?>
                                        <details>
                                            <summary style="cursor: pointer; color: var(--secondary-color);">Ver detalles</summary>
                                            <pre style="background: #f5f5f5; padding: 0.5rem; border-radius: 4px; margin-top: 0.5rem; font-size: 0.8rem; overflow-x: auto;"><?php echo htmlspecialchars(json_encode(json_decode($log['cambios']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                        </details>
                                    <?php else: ?>
                                        <em style="color: #999;">Sin detalles</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small style="font-family: monospace;"><?php echo htmlspecialchars($log['ip_address'] ?? 'N/A'); ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-top: 1.5rem;">
        <a href="<?php echo APP_URL; ?>/?url=users" class="btn btn-secondary">← Volver a Usuarios</a>
    </div>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
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

.badge-primary {
    background-color: var(--secondary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

details summary {
    font-weight: 500;
}

details[open] summary {
    margin-bottom: 0.5rem;
}
</style>
