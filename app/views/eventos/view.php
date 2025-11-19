<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header" style="background-color: #2196f3; color: white;">
                    <h3>📅 Detalle del Evento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <div class="info-section">
                        <div class="info-item">
                            <strong>Nombre:</strong>
                            <span><?= htmlspecialchars($evento['nombre']) ?></span>
                        </div>

                        <?php if (!empty($evento['descripcion'])): ?>
                            <div class="info-item">
                                <strong>Descripción:</strong>
                                <span><?= nl2br(htmlspecialchars($evento['descripcion'])) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <strong>Fecha de Inicio:</strong>
                            <span><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>Fecha de Término:</strong>
                            <span><?= date('d/m/Y', strtotime($evento['fecha_termino'])) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>Duración:</strong>
                            <span>
                                <?php
                                $inicio = new DateTime($evento['fecha_inicio']);
                                $termino = new DateTime($evento['fecha_termino']);
                                $duracion = $inicio->diff($termino);
                                
                                $dias = $duracion->days;
                                $horas = $duracion->h;
                                $minutos = $duracion->i;
                                
                                $texto_duracion = [];
                                if ($dias > 0) $texto_duracion[] = "$dias día" . ($dias > 1 ? 's' : '');
                                if ($horas > 0) $texto_duracion[] = "$horas hora" . ($horas > 1 ? 's' : '');
                                if ($minutos > 0) $texto_duracion[] = "$minutos minuto" . ($minutos > 1 ? 's' : '');
                                
                                echo implode(', ', $texto_duracion) ?: '0 minutos';
                                ?>
                            </span>
                        </div>

                        <div class="info-item">
                            <strong>Lugar:</strong>
                            <span><?= htmlspecialchars($evento['lugar']) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>Fecha de Registro:</strong>
                            <span><?= date('d/m/Y H:i', strtotime($evento['created_at'])) ?></span>
                        </div>

                        <?php if ($evento['updated_at'] !== $evento['created_at']): ?>
                            <div class="info-item">
                                <strong>Última Actualización:</strong>
                                <span><?= date('d/m/Y H:i', strtotime($evento['updated_at'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <a href="/?url=eventos/edit&id=<?= $evento['id'] ?>" class="btn btn-primary">
                            ✏️ Editar
                        </a>
                        <a href="/?url=eventos" class="btn btn-secondary">
                            ← Volver al Listado
                        </a>
                        <a href="/?url=eventos/delete&id=<?= $evento['id'] ?>" 
                           class="btn btn-danger float-right" 
                           onclick="return confirm('¿Está seguro de eliminar este evento?')">
                            🗑️ Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-section {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #dee2e6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item strong {
    min-width: 200px;
    color: #495057;
}

.info-item span {
    flex: 1;
    color: #212529;
}
</style>
