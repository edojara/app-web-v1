<div class="card">
    <div class="card-header">
        <h1 class="card-title">👥 Gestión de Usuarios</h1>
        <p class="card-subtitle">Administra los usuarios del sistema</p>
    </div>

    <?php 
    if (session_status() === PHP_SESSION_NONE) session_start();
    $isAdmin = isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1;
    // Debug: var_dump($_SESSION['user_role_id']); // Descomentar para debug
    ?>

    <?php if ($isAdmin): ?>
        <div class="mb-3">
            <a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-primary">
                ➕ Crear Nuevo Usuario
            </a>
        </div>
    <?php endif; ?>

    <?php if (count($users) > 0): ?>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">
            💡 <em>Haz doble clic en una fila para ver los detalles del usuario</em>
        </p>
        
        <!-- Vista de tabla para desktop/tablet -->
        <div style="overflow-x: auto;">
            <?php if ($isAdmin): ?>
            <!-- Tabla para administradores (CON columna Acciones) -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Autenticación</th>
                        <th>Último Acceso</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>'" style="cursor: pointer;" title="Doble click para ver detalles">
                        <td><strong><?php echo $user['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="badge badge-primary"><?php echo $user['role_nombre'] ?? 'Sin rol'; ?></span></td>
                        <td>
                            <?php if ($user['auth_type'] === 'google'): ?>
                                <span class="badge badge-danger">🔴 Google</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">🔒 Local</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['last_login']): ?>
                                <small><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></small>
                            <?php else: ?>
                                <span class="text-muted"><em>Nunca</em></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['estado'] === 'activo'): ?>
                                <span class="badge badge-success">✓ Activo</span>
                            <?php else: ?>
                                <span class="badge badge-warning">✗ Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary" style="padding: 0.35rem 0.6rem; font-size: 0.8rem; margin-right: 0.25rem;">✏️</a>
                            <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;" onclick="return confirmDelete('<?php echo htmlspecialchars($user['name']); ?>')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <!-- Tabla para usuarios NO administradores (SIN columna Acciones) -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Autenticación</th>
                        <th>Último Acceso</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>'" style="cursor: pointer;" title="Doble click para ver detalles">
                        <td><strong><?php echo $user['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="badge badge-primary"><?php echo $user['role_nombre'] ?? 'Sin rol'; ?></span></td>
                        <td>
                            <?php if ($user['auth_type'] === 'google'): ?>
                                <span class="badge badge-danger">🔴 Google</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">🔒 Local</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['last_login']): ?>
                                <small><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></small>
                            <?php else: ?>
                                <span class="text-muted"><em>Nunca</em></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['estado'] === 'activo'): ?>
                                <span class="badge badge-success">✓ Activo</span>
                            <?php else: ?>
                                <span class="badge badge-warning">✗ Inactivo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Vista de tarjetas para móvil -->
        <div class="users-mobile-view" style="display: none;">
            <?php foreach ($users as $user): ?>
                <div class="user-card-mobile" ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>'" style="cursor: pointer;">
                    <div class="user-header">
                        <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                        <?php if ($user['estado'] === 'activo'): ?>
                            <span class="badge badge-success">✓ Activo</span>
                        <?php else: ?>
                            <span class="badge badge-warning">✗ Inactivo</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="user-info">
                        <div class="user-info-row">
                            <span class="user-info-label">📧 Email:</span>
                            <span style="font-size: 0.8rem;"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        
                        <div class="user-info-row">
                            <span class="user-info-label">📋 Rol:</span>
                            <span class="badge badge-primary">
                                <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
                            </span>
                        </div>
                        
                        <div class="user-info-row">
                            <span class="user-info-label">🔑 Autenticación:</span>
                            <?php if ($user['auth_type'] === 'google'): ?>
                                <span class="badge badge-danger">Google</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Local</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="user-info-row">
                            <span class="user-info-label">🕒 Último acceso:</span>
                            <span style="font-size: 0.8rem;">
                                <?php 
                                if ($user['last_login']) {
                                    echo date('d/m/Y H:i', strtotime($user['last_login']));
                                } else {
                                    echo '<em>Nunca</em>';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if ($isAdmin): ?>
                        <div class="user-actions">
                            <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary" onclick="event.stopPropagation();" style="padding: 0.4rem 0.7rem; font-size: 0.8rem;">✏️ Editar</a>
                            <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="event.stopPropagation(); return confirmDelete('<?php echo htmlspecialchars($user['name']); ?>');" style="padding: 0.4rem 0.7rem; font-size: 0.8rem;">🗑️ Eliminar</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alerta alerta-info" style="text-align: center;">
            ℹ️ No hay usuarios registrados en el sistema.
        </div>
    <?php endif; ?>
</div>
