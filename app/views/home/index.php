<div class="card">
    <div class="card-header">
        <h1 class="card-title">🎓 Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="card-subtitle">Sistema web de acreditación educativa</p>
    </div>

    <!-- Dashboard de Estadísticas -->
    <h2 style="margin-top: 1.5rem; margin-bottom: 1rem;">📊 Panel de Control</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Eventos Próximos -->
        <a href="/?url=eventos" style="text-decoration: none;">
            <div style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 2.5rem;">🔔</span>
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Eventos Próximos</div>
                        <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $eventosProximos ?></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Eventos Realizados -->
        <a href="/?url=eventos" style="text-decoration: none;">
            <div style="background: linear-gradient(135deg, #757575 0%, #616161 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 2.5rem;">📋</span>
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Eventos Realizados</div>
                        <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $eventosRealizados ?></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Instituciones -->
        <a href="/?url=instituciones" style="text-decoration: none;">
            <div style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 2.5rem;">🏛️</span>
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Instituciones</div>
                        <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $totalInstituciones ?></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Participantes -->
        <a href="/?url=participantes" style="text-decoration: none;">
            <div style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)'">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 2.5rem;">👥</span>
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Participantes</div>
                        <div style="font-size: 2.5rem; font-weight: bold; line-height: 1;"><?= $totalParticipantes ?></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Sección de indicadores adicionales -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
        <!-- Top 5 Instituciones con más participantes -->
        <div class="card">
            <h2 style="color: #1976d2; margin-bottom: 20px;">🏆 Top 5 Instituciones con Más Participantes</h2>
            
            <?php if (!empty($topInstitucionesParticipantes)): ?>
                <div style="max-width: 100%;">
                    <?php foreach ($topInstitucionesParticipantes as $index => $institucion): ?>
                        <div style="display: flex; align-items: center; padding: 15px; margin-bottom: 10px; background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%); border-radius: 8px; border-left: 4px solid <?php 
                            echo match($index) {
                                0 => '#ffd700',  // Oro
                                1 => '#c0c0c0',  // Plata
                                2 => '#cd7f32',  // Bronce
                                default => '#1976d2'  // Azul
                            };
                        ?>;">
                            <div style="font-size: 24px; font-weight: bold; color: #424242; min-width: 40px; text-align: center;">
                                <?php 
                                    echo match($index) {
                                        0 => '🥇',
                                        1 => '🥈',
                                        2 => '🥉',
                                        default => ($index + 1)
                                    };
                                ?>
                            </div>
                            <div style="flex: 1; padding: 0 20px;">
                                <div class="institucion-nombre" style="font-weight: 600; color: #212121; font-size: 16px;">
                                    <?php echo htmlspecialchars($institucion['nombre']); ?>
                                </div>
                            </div>
                            <div style="background: #1976d2; color: white; padding: 8px 20px; border-radius: 20px; font-weight: bold; font-size: 18px;">
                                <?php echo number_format($institucion['total_participantes']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #757575; padding: 20px;">
                    No hay datos disponibles
                </p>
            <?php endif; ?>
        </div>

        <!-- Top 5 Instituciones con más asistencia -->
        <div class="card">
            <h2 style="color: #2e7d32; margin-bottom: 20px;">✅ Top 5 Instituciones con Más Asistencia</h2>
            
            <?php if (!empty($topInstitucionesAsistencia)): ?>
                <div style="max-width: 100%;">
                    <?php foreach ($topInstitucionesAsistencia as $index => $institucion): ?>
                        <div style="display: flex; align-items: center; padding: 15px; margin-bottom: 10px; background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%); border-radius: 8px; border-left: 4px solid <?php 
                            echo match($index) {
                                0 => '#ffd700',  // Oro
                                1 => '#c0c0c0',  // Plata
                                2 => '#cd7f32',  // Bronce
                                default => '#2e7d32'  // Verde
                            };
                        ?>;">
                            <div style="font-size: 24px; font-weight: bold; color: #424242; min-width: 40px; text-align: center;">
                                <?php 
                                    echo match($index) {
                                        0 => '🥇',
                                        1 => '🥈',
                                        2 => '🥉',
                                        default => ($index + 1)
                                    };
                                ?>
                            </div>
                            <div style="flex: 1; padding: 0 20px;">
                                <div class="institucion-nombre" style="font-weight: 600; color: #212121; font-size: 16px;">
                                    <?php echo htmlspecialchars($institucion['nombre']); ?>
                                </div>
                            </div>
                            <div style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); color: white; padding: 8px 20px; border-radius: 20px; font-weight: bold; font-size: 18px; box-shadow: 0 2px 4px rgba(46,125,50,0.3);">
                                <?php echo number_format($institucion['total_asistencias']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #757575; padding: 20px;">
                    📊 No hay datos de asistencia disponibles aún
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
