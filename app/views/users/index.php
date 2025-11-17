<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Solo administradores pueden ver esta página
if (!isset($_SESSION['user_role_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: ' . APP_URL . '/?url=home');
    exit();
}

$isAdmin = true;
?>

<div class="card">
    <div class="card-header">
        <h1 class="card-title">👥 Gestión de Usuarios</h1>
        <p class="card-subtitle">Administra los usuarios del sistema</p>
    </div>

    <div class="mb-3">
        <a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-primary">
            ➕ Crear Nuevo Usuario
        </a>
    </div>

    <?php if (count($users) > 0): ?>
    
    <style>
        .users-grid {
            display: grid;
            grid-template-columns: 50px 200px 250px 120px 130px 150px 100px 120px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        .users-grid-header {
            display: contents;
        }
        .users-grid-header > div {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            font-weight: 600;
            border-bottom: 2px solid #fff;
        }
        .users-grid-row {
            display: contents;
            cursor: pointer;
        }
        .users-grid-row > div {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .users-grid-row:hover > div {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
    </style>

    <div class="users-grid">
        <!-- Header -->
        <div class="users-grid-header">
            <div>ID</div>
            <div>Nombre</div>
            <div>Email</div>
            <div>Rol</div>
            <div>Autenticación</div>
            <div>Último Acceso</div>
            <div>Estado</div>
            <div class="text-center">Acciones</div>
        </div>
        
        <!-- Rows -->
        <?php foreach ($users as $user): ?>
        <div class="users-grid-row" ondblclick="window.location.href='<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>'" title="Doble click para ver detalles">
            <div><?php echo $user['id']; ?></div>
            <div><?php echo htmlspecialchars($user['name']); ?></div>
            <div><?php echo htmlspecialchars($user['email']); ?></div>
            <div>
                <span class="badge badge-primary">
                    <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
                </span>
            </div>
            <div>
                <?php if ($user['auth_type'] === 'google'): ?>
                <span class="badge badge-danger">🔴 Google</span>
                <?php else: ?>
                <span class="badge badge-secondary">🔒 Local</span>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($user['last_login']): ?>
                    <?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?>
                <?php else: ?>
                    <em>Nunca</em>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($user['estado'] === 'activo'): ?>
                <span class="badge badge-success">✓ Activo</span>
                <?php else: ?>
                <span class="badge badge-warning">✗ Inactivo</span>
                <?php endif; ?>
            </div>
            <div class="text-center">
                <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" 
                   class="btn btn-sm btn-primary" style="margin-right: 0.25rem;">✏️</a>
                <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('¿Eliminar usuario <?php echo htmlspecialchars($user['name']); ?>?')">🗑️</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
        </tbody>
    </table>

    <?php else: ?>
    <div class="alerta alerta-info">
        ℹ️ No hay usuarios registrados en el sistema.
    </div>
    <?php endif; ?>
</div>
