<div class="card">
    <div class="card-header">
        <h1 class="card-title">🎓 Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="card-subtitle">Sistema web de acreditación educativa</p>
    </div>

    <!-- Dashboard de Estadísticas -->
    <h2 style="margin-top: 1.5rem; margin-bottom: 1rem;">📊 Panel de Control</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Eventos Próximos -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <span style="font-size: 2.5rem;">🔔</span>
                <div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Eventos Próximos</div>
                    <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $eventosProximos ?></div>
                </div>
            </div>
            <a href="/?url=eventos" style="color: white; text-decoration: none; font-size: 0.875rem; opacity: 0.9;">Ver todos →</a>
        </div>

        <!-- Eventos Realizados -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <span style="font-size: 2.5rem;">📋</span>
                <div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Eventos Realizados</div>
                    <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $eventosRealizados ?></div>
                </div>
            </div>
            <a href="/?url=eventos" style="color: white; text-decoration: none; font-size: 0.875rem; opacity: 0.9;">Ver historial →</a>
        </div>

        <!-- Total Instituciones -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <span style="font-size: 2.5rem;">🏛️</span>
                <div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Instituciones</div>
                    <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $totalInstituciones ?></div>
                </div>
            </div>
            <a href="/?url=instituciones" style="color: white; text-decoration: none; font-size: 0.875rem; opacity: 0.9;">Administrar →</a>
        </div>

        <!-- Total Participantes -->
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <span style="font-size: 2.5rem;">👥</span>
                <div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Participantes</div>
                    <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $totalParticipantes ?></div>
                </div>
            </div>
            <a href="/?url=participantes" style="color: white; text-decoration: none; font-size: 0.875rem; opacity: 0.9;">Administrar →</a>
        </div>
    </div>

    <h2 style="margin-top: 2rem;">✨ Características</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
        <div class="flex gap-1">
            <span>✓</span>
            <span>Estructura MVC moderna</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Enrutamiento limpio</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Autenticación dual (Local + OAuth2)</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Base de datos MySQL</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Control de acceso por roles</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Auditoría de cambios</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Interfaz responsive</span>
        </div>
        <div class="flex gap-1">
            <span>✓</span>
            <span>Diseño profesional</span>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h2 class="card-title">👥 Usuarios del Sistema</h2>
    </div>
    
    <?php if (count($users) > 0): ?>
        <div style="overflow-x: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong><?php echo $user['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo isset($user['role_nombre']) ? htmlspecialchars($user['role_nombre']) : 'Sin rol'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['estado'] === 'activo'): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Inactivo</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alerta alerta-info">
            ℹ️ No hay usuarios registrados en la base de datos.
        </div>
    <?php endif; ?>
</div>
