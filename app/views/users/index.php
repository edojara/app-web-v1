<h1>Gestión de Usuarios</h1>

<a href="<?php echo APP_URL; ?>/?url=users/create" class="btn btn-primary" style="display: inline-block; margin-bottom: 1.5rem;">
    + Nuevo Usuario
</a>

<?php if (count($users) > 0): ?>
    <table style="width: 100%; border-collapse: collapse; background-color: white;">
        <thead>
            <tr style="background-color: #2c3e50; color: white;">
                <th style="padding: 1rem; text-align: left; border: 1px solid #ddd;">ID</th>
                <th style="padding: 1rem; text-align: left; border: 1px solid #ddd;">Nombre</th>
                <th style="padding: 1rem; text-align: left; border: 1px solid #ddd;">Email</th>
                <th style="padding: 1rem; text-align: left; border: 1px solid #ddd;">Rol</th>
                <th style="padding: 1rem; text-align: left; border: 1px solid #ddd;">Estado</th>
                <th style="padding: 1rem; text-align: center; border: 1px solid #ddd;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #ddd; hover: background-color: #f9f9f9;">
                    <td style="padding: 1rem; border: 1px solid #ddd;"><?php echo $user['id']; ?></td>
                    <td style="padding: 1rem; border: 1px solid #ddd;"><?php echo htmlspecialchars($user['name']); ?></td>
                    <td style="padding: 1rem; border: 1px solid #ddd;"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td style="padding: 1rem; border: 1px solid #ddd;">
                        <span style="background-color: #3498db; color: white; padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 0.85rem;">
                            <?php echo $user['role_nombre'] ?? 'Sin rol'; ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; border: 1px solid #ddd;">
                        <span style="background-color: <?php echo $user['estado'] === 'activo' ? '#27ae60' : '#e74c3c'; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 4px; font-size: 0.85rem;">
                            <?php echo ucfirst($user['estado']); ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; border: 1px solid #ddd; text-align: center;">
                        <a href="<?php echo APP_URL; ?>/?url=users/view&id=<?php echo $user['id']; ?>" style="color: #3498db; text-decoration: none; margin: 0 0.5rem;">Ver</a>
                        <a href="<?php echo APP_URL; ?>/?url=users/edit&id=<?php echo $user['id']; ?>" style="color: #f39c12; text-decoration: none; margin: 0 0.5rem;">Editar</a>
                        <a href="<?php echo APP_URL; ?>/?url=users/delete&id=<?php echo $user['id']; ?>" style="color: #e74c3c; text-decoration: none; margin: 0 0.5rem;" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="color: #999; font-style: italic;">No hay usuarios registrados.</p>
<?php endif; ?>

<style>
    .btn {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    
    .btn-primary {
        background-color: #3498db;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
    }
</style>
