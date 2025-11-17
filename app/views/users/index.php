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
        <div class="page-header">
        <h1>👥 Gestión de Usuarios</h1>
        <div class="page-actions">
            <a href="<?php echo APP_URL; ?>/?url=audit" class="btn btn-secondary">📋 Ver Auditoría</a>
            <a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-success">➕ Crear Usuario</a>
        </div>
    </div>

    <?php if (count($users) > 0): ?>
    
    <style>
        .users-grid {
            display: grid;
            grid-template-columns: 
                minmax(40px, 60px)      /* ID */
                minmax(150px, 1fr)      /* Nombre */
                minmax(180px, 1.5fr)    /* Email */
                minmax(100px, 0.8fr)    /* Rol */
                minmax(110px, 0.9fr)    /* Autenticación */
                minmax(120px, 1fr)      /* Último Acceso */
                minmax(80px, 0.6fr)     /* Estado */
                minmax(100px, 0.7fr);   /* Acciones */
            gap: 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            width: 100%;
        }
        .users-grid-header {
            display: contents;
        }
        .users-grid-header > div {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid #fff;
        }
        .users-grid-row {
            display: contents;
            cursor: pointer;
        }
        .users-grid-row > div {
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #f0f0f0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .users-grid-row:hover > div {
            background-color: #e3f2fd;
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
            font-size: 1rem;
            transition: all 0.2s ease;
            margin: 0 0.15rem;
        }
        .btn-edit {
            background: #e3f2fd;
            color: #1976d2;
        }
        .btn-edit:hover {
            background: #1976d2;
            color: white;
            transform: scale(1.1);
        }
        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }
        .btn-delete:hover {
            background: #d32f2f;
            color: white;
            transform: scale(1.1);
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
            <div title="<?php echo htmlspecialchars($user['name']); ?>"><?php echo htmlspecialchars($user['name']); ?></div>
            <div title="<?php echo htmlspecialchars($user['email']); ?>"><?php echo htmlspecialchars($user['email']); ?></div>
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
                    <small><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></small>
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
            <div class="text-center" style="white-space: normal;">
                <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" 
                   class="btn-action btn-edit" title="Editar">✏️</a>
                <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" 
                   class="btn-action btn-delete" title="Eliminar"
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
