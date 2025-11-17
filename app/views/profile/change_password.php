<?php
// Vista: cambiar contraseña
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2>Cambiar Contraseña</h2>

    <?php if (!empty($error)): ?>
        <div style="color: #c0392b; margin-bottom: 1rem; padding: 0.8rem; background-color: #fadbd8; border-radius: 4px; border-left: 4px solid #c0392b;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="color: #155724; margin-bottom: 1rem; padding: 0.8rem; background-color: #d4edda; border-radius: 4px; border-left: 4px solid #28a745;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($user['auth_type'] !== 'local'): ?>
        <div style="color: #856404; margin-bottom: 1rem; padding: 0.8rem; background-color: #fff3cd; border-radius: 4px; border-left: 4px solid #ffc107;">
            No puedes cambiar la contraseña de una cuenta OAuth (Google). Las cuentas OAuth están protegidas por Google.
        </div>
    <?php else: ?>
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 1.5rem;">
            <form method="post" action="<?php echo APP_URL; ?>/?url=profile/change-password">
                <div style="margin-bottom: 1.5rem;">
                    <label for="current_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Contraseña Actual</label>
                    <input type="password" id="current_password" name="current_password" required 
                        style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="new_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nueva Contraseña</label>
                    <input type="password" id="new_password" name="new_password" required 
                        style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
                    <small style="color: #666;">Mínimo 6 caracteres.</small>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                        style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" style="
                        padding: 0.8rem 1.5rem;
                        background: #28a745;
                        color: white;
                        border: none;
                        border-radius: 4px;
                        font-size: 1rem;
                        cursor: pointer;
                        transition: background 0.3s;
                    ">Cambiar Contraseña</button>

                    <a href="<?php echo APP_URL; ?>/?url=profile" style="
                        padding: 0.8rem 1.5rem;
                        background: #6c757d;
                        color: white;
                        text-decoration: none;
                        border-radius: 4px;
                        display: inline-block;
                        transition: background 0.3s;
                    ">Cancelar</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
