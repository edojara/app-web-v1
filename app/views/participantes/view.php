<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Detalle del Participante</h3>
                    <div>
                        <a href="/?url=participantes/edit&id=<?= $participante['id'] ?>" class="btn btn-warning btn-sm">
                            ✏️ Editar
                        </a>
                        <a href="/?url=participantes/delete&id=<?= $participante['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Está seguro de eliminar este participante?')">
                            🗑️ Eliminar
                        </a>
                        <a href="/?url=participantes" class="btn btn-secondary btn-sm">
                            ← Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Nombre Completo:</strong>
                            <span><?= htmlspecialchars($participante['nombre_completo']) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>RUT:</strong>
                            <span><?= htmlspecialchars($participante['rut']) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>Institución:</strong>
                            <span>
                                <?php if ($participante['institucion_nombre']): ?>
                                    <a href="/?url=instituciones/view&id=<?= $participante['institucion_id'] ?>">
                                        <?= htmlspecialchars($participante['institucion_nombre']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Sin institución</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="info-item">
                            <strong>Teléfono:</strong>
                            <span>
                                <?php if ($participante['telefono']): ?>
                                    <a href="tel:<?= htmlspecialchars($participante['telefono']) ?>">
                                        <?= htmlspecialchars($participante['telefono']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No registrado</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="info-item">
                            <strong>Fecha de Registro:</strong>
                            <span><?= date('d/m/Y H:i', strtotime($participante['created_at'])) ?></span>
                        </div>

                        <div class="info-item">
                            <strong>Última Actualización:</strong>
                            <span><?= date('d/m/Y H:i', strtotime($participante['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    padding: 1rem 0;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item strong {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 1.1rem;
    color: #212529;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
