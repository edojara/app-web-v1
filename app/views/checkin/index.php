<?php
/**
 * Función para abreviar Universidad a Univ en visualización
 */
function abreviarInstitucion($nombre) {
    if (!$nombre) return $nombre;
    return str_replace('Universidad', 'Univ', $nombre);
}
?>
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <a href="?url=inscripciones&evento_id=<?php echo $evento_id; ?>" class="btn">← Volver a Inscripciones</a>
            <a href="?url=eventos" class="btn">📋 Ver Eventos</a>
        </div>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="color: #666; margin-bottom: 10px;">✅ Check-in de Asistencia</h3>
            <h1 style="margin-bottom: 10px;"><?php echo htmlspecialchars($evento['nombre']); ?></h1>
            <p style="color: #666; margin-top: 5px;">
                📅 <?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?> - 
                <?php echo date('d/m/Y', strtotime($evento['fecha_termino'])); ?> | 
                📍 <?php echo htmlspecialchars($evento['lugar']); ?>
            </p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Selector de fecha -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <label style="font-weight: 600; margin-bottom: 10px; display: block;">Seleccionar fecha del evento:</label>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <?php foreach ($fechas_evento as $fecha): 
                    $es_hoy = $fecha === date('Y-m-d');
                    $es_seleccionada = $fecha === $fecha_seleccionada;
                    $checkins_fecha = $this->checkinModel->contarByEventoYFecha($evento_id, $fecha);
                ?>
                    <a href="?url=checkin&evento_id=<?php echo $evento_id; ?>&fecha=<?php echo $fecha; ?>" 
                       class="btn" 
                       style="<?php echo $es_seleccionada ? 'background: #2196f3; color: white;' : 'background: white;'; ?> position: relative; padding: 12px 20px;">
                        <?php if ($es_hoy): ?>
                            <span style="font-size: 11px; display: block; color: <?php echo $es_seleccionada ? '#e3f2fd' : '#2196f3'; ?>;">HOY</span>
                        <?php endif; ?>
                        <strong><?php echo date('d/m/Y', strtotime($fecha)); ?></strong>
                        <br>
                        <small style="font-size: 12px;"><?php echo $checkins_fecha; ?> check-ins</small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Resumen del día -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3;">
                <div style="font-size: 14px; color: #1976d2; margin-bottom: 5px;">Total Inscritos</div>
                <div style="font-size: 32px; font-weight: bold; color: #1976d2;"><?php echo $total_inscritos; ?></div>
            </div>
            <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #4caf50;">
                <div style="font-size: 14px; color: #2e7d32; margin-bottom: 5px;">Check-ins Realizados</div>
                <div style="font-size: 32px; font-weight: bold; color: #2e7d32;"><?php echo $checkins_hoy; ?></div>
            </div>
            <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #ff9800;">
                <div style="font-size: 14px; color: #e65100; margin-bottom: 5px;">Pendientes</div>
                <div style="font-size: 32px; font-weight: bold; color: #e65100;"><?php echo $total_inscritos - $checkins_hoy; ?></div>
            </div>
        </div>

        <!-- Búsqueda rápida por RUT -->
        <div style="background: white; padding: 25px; border-radius: 8px; border: 2px solid #2196f3; margin-bottom: 20px;">
            <h3 style="color: #2196f3; margin-bottom: 15px;">🔍 Check-in Rápido por RUT</h3>
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 1;">
                    <input type="text" 
                           id="rutBusqueda" 
                           placeholder="Ingrese RUT del participante (ej: 12.345.678-9)" 
                           class="form-control"
                           style="font-size: 18px; padding: 15px;"
                           autofocus>
                </div>
                <button onclick="buscarYRegistrarCheckin()" class="btn btn-primary" style="padding: 15px 30px; font-size: 16px;">
                    ✅ Registrar Check-in
                </button>
            </div>
            <div id="resultadoBusqueda" style="margin-top: 15px;"></div>
        </div>

        <!-- Lista de participantes -->
        <div style="margin-top: 20px;">
            <h2 style="color: #1976d2; margin-bottom: 15px;">Lista de Participantes</h2>
            
            <!-- Filtro de búsqueda -->
            <div style="margin-bottom: 15px;">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control" 
                    placeholder="Buscar por nombre, RUT o institución..." 
                    style="max-width: 600px;">
            </div>
            
            <?php if (!empty($inscripciones)): ?>
                <!-- Contenedor de la tabla -->
                <div id="tableContainer"></div>
                
                <!-- Paginación -->
                <div class="pagination-container" style="margin-top: 1.5rem;">
                    <div class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <label for="recordsPerPage">Mostrar: </label>
                            <select id="recordsPerPage" onchange="changeRecordsPerPage()" class="form-control" style="display: inline-block; width: auto;">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">Todos</option>
                            </select>
                            <span id="recordsInfo" style="margin-left: 1rem;"></span>
                        </div>
                        <div id="paginationButtons"></div>
                    </div>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 40px;">
                    No hay participantes inscritos en este evento
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Estilos para paginación */
.pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.pagination-controls > div {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

#paginationButtons {
    display: flex;
    gap: 0.25rem;
}

#paginationButtons .btn {
    min-width: 40px;
}
</style>

<script>
const inscripcionesData = <?php echo json_encode($inscripciones); ?>;
const eventoId = <?php echo $evento_id; ?>;
const fechaSeleccionada = '<?php echo $fecha_seleccionada; ?>';
let filteredData = [...inscripcionesData];
let currentPage = 1;
let recordsPerPage = 20;

// Función para renderizar la tabla
function renderTable() {
    const container = document.getElementById('tableContainer');
    if (!container) return;
    
    const totalRecords = filteredData.length;
    const totalPages = recordsPerPage === 'all' ? 1 : Math.ceil(totalRecords / recordsPerPage);
    
    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }
    
    const startIndex = recordsPerPage === 'all' ? 0 : (currentPage - 1) * recordsPerPage;
    const endIndex = recordsPerPage === 'all' ? totalRecords : startIndex + parseInt(recordsPerPage);
    const pageData = filteredData.slice(startIndex, endIndex);
    
    let html = '<div style="display: grid; grid-template-columns: 50px 2.5fr 1fr 2fr 1fr 120px; gap: 0; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
    
    // Header
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">#</div>';
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Nombre Completo</div>';
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">RUT</div>';
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0;">Institución</div>';
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; border-right: 1px solid #1565c0; text-align: center;">Check-ins</div>';
    html += '<div style="background: #1976d2; color: white; padding: 12px; font-weight: 600; text-align: center;">Estado Hoy</div>';
    
    // Data rows
    pageData.forEach((inscripcion, index) => {
        const globalIndex = startIndex + index + 1;
        const bgColor = index % 2 == 0 ? '#f8f9fa' : 'white';
        const institucionAbrev = inscripcion.institucion_nombre ? inscripcion.institucion_nombre.replace('Universidad', 'Univ') : 'Sin institución';
        const tieneCheckin = inscripcion.tiene_checkin_hoy;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;"><strong>${globalIndex}</strong></div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
            <a href="javascript:void(0)" 
               onclick="verDetalleParticipante(${inscripcion.participante_id})"
               style="color: #1976d2; text-decoration: none; font-weight: bold; cursor: pointer;"
               onmouseover="this.style.textDecoration='underline'"
               onmouseout="this.style.textDecoration='none'"
               title="Ver detalles del participante">
                ${inscripcion.nombre_completo}
            </a>
        </div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">${inscripcion.rut}</div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center;">
            <span style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                ${institucionAbrev}
            </span>
        </div>`;
        
        html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: center;">
            <span style="font-weight: bold; color: #2196f3;">${inscripcion.total_checkins}</span>
        </div>`;
        
        if (tieneCheckin) {
            html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: center;">
                <span style="background: #4caf50; color: white; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    ✓ Presente
                </span>
            </div>`;
        } else {
            html += `<div style="background: ${bgColor}; padding: 12px; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: center;">
                <button onclick="registrarCheckin(${inscripcion.id})" 
                        class="btn btn-primary" 
                        style="padding: 6px 12px; font-size: 13px;">
                    ✅ Registrar
                </button>
            </div>`;
        }
    });
    
    html += '</div>';
    container.innerHTML = html;
    
    updatePaginationControls(totalPages, totalRecords, startIndex + 1, Math.min(endIndex, totalRecords));
}

// Actualizar controles de paginación
function updatePaginationControls(totalPages, totalRecords, startRecord, endRecord) {
    const container = document.getElementById('paginationButtons');
    const infoContainer = document.getElementById('recordsInfo');
    
    if (!container) return;
    
    infoContainer.textContent = `Mostrando ${startRecord} a ${endRecord} de ${totalRecords} registros`;
    
    if (recordsPerPage === 'all' || totalPages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '';
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>← Anterior</button>`;
    
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);
    
    if (endPage - startPage < maxButtons - 1) {
        startPage = Math.max(1, endPage - maxButtons + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}" onclick="goToPage(${i})">${i}</button>`;
    }
    
    html += `<button class="btn btn-sm btn-outline-primary" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Siguiente →</button>`;
    
    container.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    renderTable();
}

function changeRecordsPerPage() {
    const select = document.getElementById('recordsPerPage');
    recordsPerPage = select.value === 'all' ? 'all' : parseInt(select.value);
    currentPage = 1;
    renderTable();
}

function filterParticipantes() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    filteredData = inscripcionesData.filter(inscripcion => {
        return inscripcion.nombre_completo.toLowerCase().includes(searchTerm) ||
               inscripcion.rut.toLowerCase().includes(searchTerm) ||
               (inscripcion.institucion_nombre && inscripcion.institucion_nombre.toLowerCase().includes(searchTerm));
    });
    
    currentPage = 1;
    renderTable();
}

// Registrar check-in individual
function registrarCheckin(inscripcionId) {
    if (confirm('¿Confirmar check-in para este participante?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?url=checkin/registrar';
        
        const inputInscripcion = document.createElement('input');
        inputInscripcion.type = 'hidden';
        inputInscripcion.name = 'inscripcion_id';
        inputInscripcion.value = inscripcionId;
        
        const inputEvento = document.createElement('input');
        inputEvento.type = 'hidden';
        inputEvento.name = 'evento_id';
        inputEvento.value = eventoId;
        
        const inputFecha = document.createElement('input');
        inputFecha.type = 'hidden';
        inputFecha.name = 'fecha';
        inputFecha.value = fechaSeleccionada;
        
        form.appendChild(inputInscripcion);
        form.appendChild(inputEvento);
        form.appendChild(inputFecha);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Búsqueda y registro rápido por RUT
function buscarYRegistrarCheckin() {
    const rutInput = document.getElementById('rutBusqueda');
    const rut = rutInput.value.trim();
    const resultado = document.getElementById('resultadoBusqueda');
    
    if (!rut) {
        resultado.innerHTML = '<div class="alert alert-error">Por favor ingrese un RUT</div>';
        return;
    }
    
    resultado.innerHTML = '<div style="color: #666;">🔄 Procesando...</div>';
    
    fetch('?url=checkin/buscarPorRut', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `rut=${encodeURIComponent(rut)}&evento_id=${eventoId}&fecha=${fechaSeleccionada}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultado.innerHTML = `
                <div class="alert alert-success">
                    <strong>✅ ${data.message}</strong><br>
                    Participante: ${data.participante.nombre_completo}<br>
                    Hora: ${data.hora}
                </div>
            `;
            rutInput.value = '';
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            resultado.innerHTML = `<div class="alert alert-error">${data.message}</div>`;
        }
    })
    .catch(error => {
        resultado.innerHTML = '<div class="alert alert-error">Error al procesar la solicitud</div>';
    });
}

// Event listener para Enter en campo de RUT
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterParticipantes);
    }
    
    const rutInput = document.getElementById('rutBusqueda');
    if (rutInput) {
        rutInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarYRegistrarCheckin();
            }
        });
    }
    
    // Renderizar tabla inicial
    if (inscripcionesData.length > 0) {
        renderTable();
    }
});
</script>
