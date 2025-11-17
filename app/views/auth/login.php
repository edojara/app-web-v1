<?php
// Vista: formulario de login
?>
<div style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
    <div style="width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Iniciar sesión</h2>

        <?php if (!empty($error)): ?>
            <div style="color: #c0392b; margin-bottom: 1rem; padding: 0.8rem; background-color: #fadbd8; border-radius: 4px; border-left: 4px solid #c0392b;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo APP_URL; ?>/?url=auth/login">
            <div style="margin-bottom: 1.2rem;">
                <label for="email" style="display: block; margin-bottom: 0.4rem; font-weight: 500;">Correo</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
            </div>
            <div style="margin-bottom: 1.8rem;">
                <label for="password" style="display: block; margin-bottom: 0.4rem; font-weight: 500;">Contraseña</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
            </div>
            <div style="text-align: center;">
                <button type="submit" style="padding: 0.8rem 2rem; background: #2c3e50; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background 0.3s; font-weight: 500;">Ingresar</button>
            </div>
        </form>
    </div>
</div>
