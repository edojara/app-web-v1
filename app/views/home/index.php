<div class="card">
    <div class="card-header">
        <h1 class="card-title">🎓 Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="card-subtitle">Sistema web de acreditación educativa</p>
    </div>

    <!-- Dashboard de Estadísticas -->
    <h2 style="margin-top: 1.5rem; margin-bottom: 1rem;">📊 Panel de Control</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Eventos Próximos -->
        <div style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
        <div style="background: linear-gradient(135deg, #757575 0%, #616161 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
        <div style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
        <div style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
</div>
