<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h1 class="card-title">➕ Crear Nuevo Usuario</h1>
    </div>

    <?php if (!empty($mensaje)): echo $mensaje; endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="name">👤 Nombre Completo</label>
            <input type="text" id="name" name="name" placeholder="Ej: Juan Pérez" required>
        </div>
        
        <div class="form-group">
            <label for="email">📧 Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ej: juan@ejemplo.com" required>
        </div>

        <div class="form-group">
            <label for="auth_type">🔑 Tipo de Autenticación</label>
            <select id="auth_type" name="auth_type" required>
                <option value="local">🔒 Cuenta Local (con contraseña)</option>
                <option value="google">🔴 Google OAuth2 (sin contraseña)</option>
            </select>
            <small style="display: block; margin-top: 0.3rem; color: #666;">
                Los usuarios con Google OAuth ingresan con sus credenciales de Google
            </small>
        </div>
        
        <div id="password_field" class="form-group">
            <label for="password">🔐 Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña segura">
        </div>
        
        <div class="form-group">
            <label for="role_id">📋 Rol del Usuario</label>
            <select id="role_id" name="role_id" required>
                <option value="">-- Selecciona un rol --</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>">
                        <?php echo htmlspecialchars($role['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="btn btn-success">✓ Crear Usuario</button>
            <a href="<?php echo APP_URL; ?>/?url=users" class="btn btn-secondary">✕ Cancelar</a>
        </div>
    </form>
</div>
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
</style>
