<h1>Bienvenido a <?php echo APP_NAME; ?></h1>
<p>Esta es tu aplicación web desarrollada con LAMP (Linux, Apache, MySQL, PHP).</p>

<h2 style="margin-top: 2rem;">Características</h2>
<ul style="margin-left: 2rem; margin-top: 1rem;">
    <li>✓ Estructura MVC (Model-View-Controller)</li>
    <li>✓ Enrutamiento limpio con .htaccess</li>
    <li>✓ Configuración centralizada</li>
    <li>✓ Seguridad básica implementada</li>
    <li>✓ Conexión a Base de Datos MySQL</li>
    <li>✓ Interfaz responsive</li>
</ul>

<h2 style="margin-top: 2rem;">Usuarios en la Base de Datos</h2>
<?php if (count($users) > 0): ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 0.5rem; text-align: left; border: 1px solid #ddd;">ID</th>
                <th style="padding: 0.5rem; text-align: left; border: 1px solid #ddd;">Nombre</th>
                <th style="padding: 0.5rem; text-align: left; border: 1px solid #ddd;">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td style="padding: 0.5rem; border: 1px solid #ddd;"><?php echo $user['id']; ?></td>
                    <td style="padding: 0.5rem; border: 1px solid #ddd;"><?php echo $user['name']; ?></td>
                    <td style="padding: 0.5rem; border: 1px solid #ddd;"><?php echo $user['email']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="color: #999; font-style: italic;">No hay usuarios registrados en la base de datos.</p>
<?php endif; ?>

<div style="background-color: #e8f4f8; padding: 1.5rem; border-left: 4px solid #3498db; margin-top: 2rem; border-radius: 4px;">
    <strong>Próximos pasos:</strong>
    <ul style="margin-left: 2rem; margin-top: 0.5rem;">
        <li>Crear la base de datos en MySQL</li>
        <li>Ejecutar el script SQL para crear las tablas</li>
        <li>Actualizar las credenciales en <code>config/database.php</code></li>
        <li>Desarrollar tus controladores y vistas</li>
    </ul>
</div>
