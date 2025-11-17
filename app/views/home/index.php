<div class="card">
    <div class="card-header">
        <h1 class="card-title">🎓 Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="card-subtitle">Sistema web de acreditación educativa</p>
    </div>

    <p>Aplicación desarrollada con la pila LAMP (Linux, Apache, MySQL, PHP) con arquitectura MVC profesional.</p>

    <h2 style="margin-top: 2rem;">✨ Características</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
        <div class="flex gap-1">
            <span>✓</span>
            <span>Estructura MVC moderna</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Enrutamiento limpio</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Autenticación dual (Local + OAuth2)</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Base de datos MySQL</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Control de acceso por roles</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Auditoría de cambios</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Interfaz responsive</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Diseño profesional</span>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h2 class="card-title">👥 Usuarios del Sistema</h2>
    </div>
    
    <?php if (count($users) > 0): ?>
        <div style="overflow-x: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong><?php echo $user['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo isset($user['role_nombre']) ? htmlspecialchars($user['role_nombre']) : 'Sin rol'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['estado'] === 'activo'): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Inactivo</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alerta alerta-info">
            ℹ️ No hay usuarios registrados en la base de datos.
        </div>
    <?php endif; ?>
</div>
