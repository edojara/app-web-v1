<h1>Crear Nuevo Usuario</h1>

<?php if (!empty($mensaje)): echo $mensaje; endif; ?>

<form method="POST" style="background-color: white; padding: 2rem; border-radius: 8px; max-width: 500px;">
    <div style="margin-bottom: 1.5rem;">
        <label for="name" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Nombre:</label>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Email:</label>
        <input type="email" id="email" name="email" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Contraseña:</label>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <label for="role_id" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Rol:</label>
        <select id="role_id" name="role_id" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
            <option value="">-- Selecciona un rol --</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>">
                    <?php echo htmlspecialchars($role['nombre']); ?> - <?php echo htmlspecialchars($role['descripcion']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <button type="submit" class="btn btn-success" style="display: inline-block; margin-right: 0.5rem;">Crear Usuario</button>
        <a href="<?php echo APP_URL; ?>/public/index.php?url=users" class="btn btn-secondary" style="display: inline-block;">Cancelar</a>
    </div>
</form>

<style>
    input, select {
        box-sizing: border-box;
    }
    
    .btn {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    
    .btn-success {
        background-color: #27ae60;
        color: white;
        border: none;
    }
    
    .btn-success:hover {
        background-color: #229954;
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
</style>
