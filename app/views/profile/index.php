<?php
// Vista: perfil de usuario
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2>Mi Perfil</h2>

    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 1.5rem;">
        <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee;">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($user['role_nombre'] ?? 'N/A'); ?></p>
            <p><strong>Estado:</strong> 
                <span style="padding: 0.3rem 0.6rem; border-radius: 4px; 
                    <?php echo $user['estado'] === 'activo' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                    <?php echo htmlspecialchars($user['estado']); ?>
                </span>
            </p>
            <p><strong>Tipo de Autenticación:</strong>
                <span style="padding: 0.3rem 0.6rem; border-radius: 4px; background: #cfe2ff; color: #084298;">
                    <?php echo $user['auth_type'] === 'google' ? 'Google OAuth2' : 'Cuenta Local'; ?>
                </span>
            </p>
            <p><strong>Miembro desde:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="<?php echo APP_URL; ?>/?url=profile/edit" style="
                padding: 0.8rem 1.5rem;
                background: #2c3e50;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                display: inline-block;
                transition: background 0.3s;
            ">Editar Perfil</a>

            <?php if ($user['auth_type'] === 'local'): ?>
                <a href="<?php echo APP_URL; ?>/?url=profile/change-password" style="
                    padding: 0.8rem 1.5rem;
                    background: #17a2b8;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    display: inline-block;
                    transition: background 0.3s;
                ">Cambiar Contraseña</a>
            <?php endif; ?>

            <a href="<?php echo APP_URL; ?>/?url=auth/logout" style="
                padding: 0.8rem 1.5rem;
                background: #c0392b;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                display: inline-block;
                transition: background 0.3s;
            ">Cerrar Sesión</a>
        </div>
    </div>
</div>
