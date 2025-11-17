<?php
// Vista: formulario de login
?>
<h2>Iniciar sesión</h2>

<?php if (!empty($error)): ?>
    <div style="color: #c0392b; margin-bottom: 1rem;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="post" action="<?php echo APP_URL; ?>/?url=auth/login">
    <div style="margin-bottom: 0.5rem;">
        <label for="email">Correo</label><br>
        <input type="email" id="email" name="email" required style="width:100%; padding:0.5rem;"/>
    </div>
    <div style="margin-bottom: 0.5rem;">
        <label for="password">Contraseña</label><br>
        <input type="password" id="password" name="password" required style="width:100%; padding:0.5rem;"/>
    </div>
    <div>
        <button type="submit" style="padding:0.6rem 1rem; background:#2c3e50; color:white; border:none; border-radius:4px;">Ingresar</button>
    </div>
</form>
