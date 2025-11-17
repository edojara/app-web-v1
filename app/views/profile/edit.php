<?php
// Vista: editar perfil
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2>Editar Perfil</h2>

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

    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 1.5rem;">
        <form method="post" action="<?php echo APP_URL; ?>/?url=profile/edit">
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nombre</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required 
                    style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box;"/>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled 
                    style="width: 100%; padding: 0.8rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; box-sizing: border-box; background: #f5f5f5; cursor: not-allowed;"/>
                <small style="color: #666;">El email no puede ser modificado.</small>
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
                ">Guardar Cambios</button>

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
</div>
