<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin = isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1;
?>

<div class="card">
    <div class="card-header">
        <h1 class="card-title">👥 Gestión de Usuarios</h1>
        <p class="card-subtitle">Administra los usuarios del sistema</p>
    </div>

    <?php if ($isAdmin): ?>
    <div class="mb-3">
        <a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-primary">
            ➕ Crear Nuevo Usuario
        </a>
    </div>
    <?php endif; ?>

    <?php if (count($users) > 0): ?>
    
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
                <?php if ($isAdmin): ?>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="badge badge-primary">
                        <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
                    </span>
                </td>
                <td>
                    <?php if ($user['auth_type'] === 'google'): ?>
                    <span class="badge badge-danger">🔴 Google</span>
                    <?php else: ?>
                    <span class="badge badge-secondary">🔒 Local</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['last_login']): ?>
                        <?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?>
                    <?php else: ?>
                        <em>Nunca</em>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['estado'] === 'activo'): ?>
                    <span class="badge badge-success">✓ Activo</span>
                    <?php else: ?>
                    <span class="badge badge-warning">✗ Inactivo</span>
                    <?php endif; ?>
                </td>
                <?php if ($isAdmin): ?>
                <td>
                    <a href="<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>" 
                       class="btn btn-sm btn-info">Ver</a>
                    <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" 
                       class="btn btn-sm btn-primary">Editar</a>
                    <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar usuario <?php echo htmlspecialchars($user['name']); ?>?')">Eliminar</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
    <div class="alerta alerta-info">
        ℹ️ No hay usuarios registrados en el sistema.
    </div>
    <?php endif; ?>
</div>
