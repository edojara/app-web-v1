<div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
    <div class="info-item" style="padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <strong style="display: block; margin-bottom: 5px; color: #666;">Nombre Completo:</strong>
        <span><?= htmlspecialchars($participante['nombre_completo']) ?></span>
    </div>

    <div class="info-item" style="padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <strong style="display: block; margin-bottom: 5px; color: #666;">RUT:</strong>
        <span><?= htmlspecialchars($participante['rut']) ?></span>
    </div>

    <div class="info-item" style="padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <strong style="display: block; margin-bottom: 5px; color: #666;">Institución:</strong>
        <span>
            <?php if ($participante['institucion_nombre']): ?>
                <span class="institucion-nombre"><?= htmlspecialchars($participante['institucion_nombre']) ?></span>
            <?php else: ?>
                <span class="text-muted">Sin institución</span>
            <?php endif; ?>
        </span>
    </div>

    <div class="info-item" style="padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <strong style="display: block; margin-bottom: 5px; color: #666;">Teléfono:</strong>
        <span><?= htmlspecialchars($participante['telefono']) ?></span>
    </div>

    <div class="info-item" style="padding: 10px; background: #f8f9fa; border-radius: 4px; grid-column: 1 / -1;">
        <strong style="display: block; margin-bottom: 5px; color: #666;">Email:</strong>
        <span><?= htmlspecialchars($participante['email']) ?></span>
    </div>
</div>

<?php 
// Obtener eventos del participante si hay inscripciones
require_once MODELS_PATH . '/Inscripcion.php';
$inscripcionModel = new Inscripcion();
$inscripciones = $inscripcionModel->getByParticipante($participante['id']);
?>

<?php if (!empty($inscripciones)): ?>
    <div style="margin-top: 20px;">
        <h3 style="color: #1976d2; margin-bottom: 15px;">📅 Eventos Inscritos</h3>
        <div style="display: grid; gap: 10px;">
            <?php foreach ($inscripciones as $inscripcion): ?>
                <div style="padding: 12px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
                    <div style="font-weight: 600; color: #1976d2; margin-bottom: 5px;">
                        <?= htmlspecialchars($inscripcion['evento_nombre']) ?>
                    </div>
                    <div style="font-size: 13px; color: #666;">
                        📍 <?= htmlspecialchars($inscripcion['lugar']) ?> | 
                        📅 <?= date('d/m/Y', strtotime($inscripcion['fecha_inicio'])) ?> - 
                        <?= date('d/m/Y', strtotime($inscripcion['fecha_termino'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <div style="margin-top: 20px; text-align: center; padding: 20px; background: #f5f5f5; border-radius: 4px; color: #999;">
        No hay eventos registrados para este participante
    </div>
<?php endif; ?>

<div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; display: flex; justify-content: flex-end; gap: 10px;">
    <a href="/?url=participantes/edit&id=<?= $participante['id'] ?>" 
       class="btn btn-primary"
       onclick="cerrarModalParticipante()">
        ✏️ Editar
    </a>
    <button onclick="cerrarModalParticipante()" class="btn">
        Cerrar
    </button>
</div>
