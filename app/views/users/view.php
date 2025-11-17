<h1>Detalle de Usuario</h1>

<div style="background-color: white; padding: 2rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Nombre:</label>
        <p><?php echo htmlspecialchars($user['name']); ?></p>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Email:</label>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Rol:</label>
        <p>
            <span style="background-color: #3498db; color: white; padding: 0.3rem 0.8rem; border-radius: 4px;">
                <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
            </span>
        </p>
        <small style="color: #666;"><?php echo $user['role_descripcion'] ?? ''; ?></small>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Estado:</label>
        <p>
            <span style="background-color: <?php echo $user['estado'] === 'activo' ? '#27ae60' : '#e74c3c'; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 4px;">
                <?php echo ucfirst($user['estado']); ?>
            </span>
        </p>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Creado:</label>
        <p><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></p>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Actualizado:</label>
        <p><?php echo date('d/m/Y H:i', strtotime($user['updated_at'])); ?></p>
    </div>
</div>

<div>
    <a href="<?php echo APP_URL; ?>/public/index.php?url=users/edit&id=<?php echo $user['id']; ?>" class="btn btn-warning" style="display: inline-block; margin-right: 0.5rem;">Editar</a>
    <a href="<?php echo APP_URL; ?>/public/index.php?url=users" class="btn btn-secondary" style="display: inline-block;">Volver</a>
</div>

<style>
    .btn {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s;
    }
    
    .btn-warning {
        background-color: #f39c12;
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #e67e22;
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
</style>
