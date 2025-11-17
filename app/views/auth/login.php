<?php
// Vista: formulario de login con opciones de autenticación local y Google
?>
<div style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
    <div style="width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Iniciar sesión</h2>

        <?php if (!empty($error)): ?>
            <div style="color: #c0392b; margin-bottom: 1rem; padding: 0.8rem; background-color: #fadbd8; border-radius: 4px; border-left: 4px solid #c0392b;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login local -->
        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 0.95rem; color: #555; margin-bottom: 0.8rem; text-align: center;">Cuenta Local</h3>
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
                    <button type="submit" style="padding: 0.8rem 2rem; background: #2c3e50; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background 0.3s; font-weight: 500; width: 100%;">Ingresar</button>
                </div>
            </form>
        </div>

        <!-- Separador -->
        <div style="text-align: center; margin: 2rem 0; color: #999;">
            <hr style="margin: 0; border: none; border-top: 1px solid #ddd;">
            <span style="position: relative; top: -12px; background: white; padding: 0 0.5rem;">O</span>
        </div>

        <!-- Login con Google -->
        <div style="text-align: center;">
            <a href="<?php echo APP_URL; ?>/?url=auth/google-login" style="display: inline-block; padding: 0.8rem 2rem; background: #ffffff; border: 2px solid #DB4437; color: #DB4437; border-radius: 4px; font-size: 1rem; cursor: pointer; text-decoration: none; font-weight: 500; transition: background 0.3s;">
                <span style="margin-right: 0.5rem;">🔴</span>Iniciar con Google
            </a>
        </div>
    </div>
</div>
